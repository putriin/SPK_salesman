<?php

namespace App\Controllers;

use App\Libraries\TopsisService;
use App\Models\HasilPerhitunganModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;
use App\Models\PerhitunganSnapshotModel;
use App\Models\SalesmanModel;

class Perhitungan extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $periodOptions = $this->getPeriodeOptions();
        $periode = $this->request->getGet('periode')
            ?: ($periodOptions[0] ?? date('Y-m'));

        $currentData = $this->buildCurrentCalculationData($periode);
        $snapshotRow = $this->getSnapshotRow($periode);
        $snapshotData = $this->decodeSnapshot($snapshotRow['snapshot_json'] ?? null);
        $hasSnapshot = !empty($snapshotData);
        $isStale = $hasSnapshot && ($snapshotRow['source_hash'] ?? '') !== $currentData['source_hash'];

        $viewData = $hasSnapshot
            ? $this->buildViewDataFromSnapshot($periode, $snapshotData)
            : $this->buildEmptyViewData($periode, $currentData);

        $viewData['title'] = 'Proses Perhitungan';
        $viewData['periodOptions'] = $periodOptions;
        $viewData['selectedPeriode'] = $periode;
        $viewData['hasSnapshot'] = $hasSnapshot;
        $viewData['isStale'] = $isStale;
        $viewData['lastCalculatedAt'] = $snapshotRow['calculated_at'] ?? null;
        $viewData['canProcess'] = $currentData['can_process'];
        $viewData['currentCompleteCount'] = $currentData['validAlternativeCount'];
        $viewData['currentIncompleteCount'] = count($currentData['incompleteAlternatives']);
        $viewData['currentCriteriaCount'] = count($currentData['criteria']);

        return view('perhitungan/index', $viewData);
    }

    public function process()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $periode = trim((string) $this->request->getPost('periode'));
        if ($periode === '') {
            return redirect()->to(base_url('perhitungan'))->with('error', 'Periode wajib dipilih.');
        }

        $currentData = $this->buildCurrentCalculationData($periode);

        if (!$currentData['can_process']) {
            return redirect()->to(base_url('perhitungan?periode=' . urlencode($periode)))
                ->with('error', 'Perhitungan belum bisa dijalankan. Minimal harus ada 2 salesman dengan data lengkap pada periode yang dipilih.');
        }

        $payload = [
            'criteria' => $currentData['calculation']['criteria'],
            'weights' => $currentData['calculation']['weights'],
            'alternatives' => $currentData['calculation']['alternatives'],
            'divisors' => $currentData['calculation']['divisors'],
            'normalized' => $currentData['calculation']['normalized'],
            'weighted' => $currentData['calculation']['weighted'],
            'idealPositive' => $currentData['calculation']['idealPositive'],
            'idealNegative' => $currentData['calculation']['idealNegative'],
            'results' => $currentData['calculation']['results'],
            'incompleteAlternatives' => $currentData['incompleteAlternatives'],
            'winner' => $currentData['calculation']['results'][0] ?? null,
            'validAlternativeCount' => $currentData['validAlternativeCount'],
        ];

        $this->saveResults($periode, $currentData['calculation']['results']);
        $this->saveSnapshot($periode, $payload, $currentData['source_hash']);

        return redirect()->to(base_url('perhitungan?periode=' . urlencode($periode)))
            ->with('success', 'Perhitungan TOPSIS untuk periode ' . $periode . ' berhasil diproses dan disimpan.');
    }

    private function getPeriodeOptions(): array
    {
        $penilaianModel = new PenilaianModel();
        $snapshotModel = new PerhitunganSnapshotModel();

        $fromPenilaian = $penilaianModel
            ->select('periode')
            ->distinct()
            ->findAll();

        $fromSnapshot = $snapshotModel
            ->select('periode')
            ->distinct()
            ->findAll();

        $periods = [];
        foreach (array_merge($fromPenilaian, $fromSnapshot) as $row) {
            if (!empty($row['periode'])) {
                $periods[$row['periode']] = $row['periode'];
            }
        }

        rsort($periods);
        return array_values($periods);
    }

    private function buildCurrentCalculationData(string $periode): array
    {
        $kriteriaModel = new KriteriaModel();
        $salesmanModel = new SalesmanModel();
        $penilaianModel = new PenilaianModel();
        $topsisService = new TopsisService();

        $criteriaRaw = $kriteriaModel->orderBy('id', 'ASC')->findAll();
        $criteria = [];
        foreach ($criteriaRaw as $row) {
            $criteria[] = [
                'id'    => $row['id'],
                'kode'  => $row['kode_kriteria'] ?? $row['kode'] ?? '',
                'nama'  => $row['nama_kriteria'] ?? $row['nama'] ?? '',
                'bobot' => (float) ($row['bobot'] ?? 0),
                'tipe'  => strtolower($row['tipe'] ?? 'benefit'),
            ];
        }

        $salesmen = $salesmanModel->orderBy('id', 'ASC')->findAll();
        $penilaianRows = $penilaianModel
            ->where('periode', $periode)
            ->orderBy('salesman_id', 'ASC')
            ->orderBy('kriteria_id', 'ASC')
            ->findAll();

        $nilaiMap = [];
        foreach ($penilaianRows as $row) {
            $salesmanId = $row['salesman_id'] ?? null;
            $kriteriaId = $row['kriteria_id'] ?? null;
            $nilai = (float) ($row['nilai'] ?? 0);

            if ($salesmanId !== null && $kriteriaId !== null) {
                $nilaiMap[$salesmanId][$kriteriaId] = $nilai;
            }
        }

        $alternatives = [];
        $incompleteAlternatives = [];

        foreach ($salesmen as $salesman) {
            $scores = [];
            $isComplete = true;

            foreach ($criteria as $criterion) {
                if (isset($nilaiMap[$salesman['id']][$criterion['id']])) {
                    $scores[] = (float) $nilaiMap[$salesman['id']][$criterion['id']];
                } else {
                    $isComplete = false;
                    break;
                }
            }

            $alternative = [
                'id'     => $salesman['id'],
                'kode'   => $salesman['kode_alternatif'] ?? $salesman['kode'] ?? ('A' . $salesman['id']),
                'nama'   => $salesman['nama'] ?? '',
                'scores' => $scores,
            ];

            if ($isComplete && !empty($criteria)) {
                $alternatives[] = $alternative;
            } else {
                $incompleteAlternatives[] = $alternative;
            }
        }

        $calculation = $topsisService->calculate($criteria, $alternatives);
        $canProcess = !empty($criteria) && count($alternatives) >= 2 && !empty($calculation['results']);

        return [
            'criteria' => $criteria,
            'alternatives' => $alternatives,
            'incompleteAlternatives' => $incompleteAlternatives,
            'validAlternativeCount' => count($alternatives),
            'calculation' => $calculation,
            'can_process' => $canProcess,
            'source_hash' => $this->buildSourceHash($criteriaRaw, $salesmen, $penilaianRows),
        ];
    }

    private function buildSourceHash(array $criteriaRaw, array $salesmen, array $penilaianRows): string
    {
        return hash('sha256', json_encode([
            'criteria' => array_map(static function ($row) {
                return [
                    'id' => (int) ($row['id'] ?? 0),
                    'kode' => $row['kode_kriteria'] ?? $row['kode'] ?? '',
                    'nama' => $row['nama_kriteria'] ?? $row['nama'] ?? '',
                    'bobot' => (float) ($row['bobot'] ?? 0),
                    'tipe' => strtolower($row['tipe'] ?? 'benefit'),
                ];
            }, $criteriaRaw),
            'salesmen' => array_map(static function ($row) {
                return [
                    'id' => (int) ($row['id'] ?? 0),
                    'kode' => $row['kode_alternatif'] ?? $row['kode'] ?? '',
                    'nama' => $row['nama'] ?? '',
                ];
            }, $salesmen),
            'penilaian' => array_map(static function ($row) {
                return [
                    'salesman_id' => (int) ($row['salesman_id'] ?? 0),
                    'kriteria_id' => (int) ($row['kriteria_id'] ?? 0),
                    'nilai' => (float) ($row['nilai'] ?? 0),
                    'periode' => $row['periode'] ?? '',
                ];
            }, $penilaianRows),
        ], JSON_UNESCAPED_UNICODE));
    }

    private function saveResults(string $periode, array $results): void
    {
        $hasilModel = new HasilPerhitunganModel();

        $hasilModel->where('periode', $periode)->delete();

        foreach ($results as $row) {
            $hasilModel->insert([
                'periode'          => $periode,
                'salesman_id'      => $row['id'],
                'nilai_preferensi' => $row['preferensi'],
                'ranking'          => $row['ranking'],
                'd_plus'           => $row['d_plus'],
                'd_minus'          => $row['d_minus'],
            ]);
        }
    }

    private function saveSnapshot(string $periode, array $payload, string $sourceHash): void
    {
        $snapshotModel = new PerhitunganSnapshotModel();
        $existing = $snapshotModel->where('periode', $periode)->first();

        $data = [
            'periode' => $periode,
            'source_hash' => $sourceHash,
            'snapshot_json' => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'calculated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($existing)) {
            $snapshotModel->update($existing['id'], $data);
            return;
        }

        $snapshotModel->insert($data);
    }

    private function getSnapshotRow(string $periode): ?array
    {
        $snapshotModel = new PerhitunganSnapshotModel();
        return $snapshotModel->where('periode', $periode)->first();
    }

    private function decodeSnapshot(?string $json): array
    {
        if (empty($json)) {
            return [];
        }

        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    private function buildViewDataFromSnapshot(string $periode, array $snapshotData): array
    {
        return [
            'periode' => $periode,
            'criteria' => $snapshotData['criteria'] ?? [],
            'weights' => $snapshotData['weights'] ?? [],
            'alternatives' => $snapshotData['alternatives'] ?? [],
            'divisors' => $snapshotData['divisors'] ?? [],
            'normalized' => $snapshotData['normalized'] ?? [],
            'weighted' => $snapshotData['weighted'] ?? [],
            'idealPositive' => $snapshotData['idealPositive'] ?? [],
            'idealNegative' => $snapshotData['idealNegative'] ?? [],
            'results' => $snapshotData['results'] ?? [],
            'incompleteAlternatives' => $snapshotData['incompleteAlternatives'] ?? [],
            'winner' => $snapshotData['winner'] ?? null,
            'validAlternativeCount' => (int) ($snapshotData['validAlternativeCount'] ?? 0),
        ];
    }

    private function buildEmptyViewData(string $periode, array $currentData): array
    {
        return [
            'periode' => $periode,
            'criteria' => [],
            'weights' => [],
            'alternatives' => [],
            'divisors' => [],
            'normalized' => [],
            'weighted' => [],
            'idealPositive' => [],
            'idealNegative' => [],
            'results' => [],
            'incompleteAlternatives' => $currentData['incompleteAlternatives'] ?? [],
            'winner' => null,
            'validAlternativeCount' => (int) ($currentData['validAlternativeCount'] ?? 0),
        ];
    }
}