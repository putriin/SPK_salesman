<?php

namespace App\Controllers;

use App\Models\HasilPerhitunganModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;
use App\Models\SalesmanModel;

class CeoDashboard extends BaseController
{
    private function guardCeo()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'ceo') {
            return redirect()->to('/login')->with('errors', [
                'login' => 'Halaman ini hanya untuk CEO.',
            ]);
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->guardCeo()) {
            return $redirect;
        }

        $salesmanModel = new SalesmanModel();
        $kriteriaModel = new KriteriaModel();
        $penilaianModel = new PenilaianModel();
        $hasilModel = new HasilPerhitunganModel();

        $totalSalesman = $salesmanModel->countAllResults();
        $totalKriteria = $kriteriaModel->countAllResults();
        $totalPeriode = $hasilModel->select('periode')->distinct()->countAllResults();
        $totalHasilAkhir = $hasilModel->countAllResults();

        $periodeRows = $hasilModel
            ->select('periode')
            ->distinct()
            ->orderBy('periode', 'DESC')
            ->findAll();

        $periodeOptions = array_map(static function ($row) {
            return $row['periode'];
        }, $periodeRows);

        $selectedPeriode = $this->request->getGet('periode');

        if (empty($selectedPeriode) && !empty($periodeOptions)) {
            $selectedPeriode = $periodeOptions[0];
        }

        $results = [];
        $chartLabels = [];
        $chartValues = [];
        $chartHighlightColors = [];
        $chartTitle = 'Grafik Preferensi Salesman';
        $topSalesmanSummary = [
            'nama' => '-',
            'kode' => '-',
            'preferensi' => 0,
            'ranking' => '-',
        ];
        $topCriteriaInsight = [
            'nama_kriteria' => '-',
            'nilai_tertinggi' => '-',
            'salesman' => '-',
            'deskripsi' => 'Belum ada data penilaian pada periode ini.',
        ];

        if (!empty($selectedPeriode)) {
            $hasilRows = $hasilModel
                ->where('periode', $selectedPeriode)
                ->orderBy('ranking', 'ASC')
                ->findAll();

            $salesmanIds = array_column($hasilRows, 'salesman_id');
            $salesmanMap = [];

            if (!empty($salesmanIds)) {
                $salesmanRows = $salesmanModel
                    ->whereIn('id', $salesmanIds)
                    ->findAll();

                foreach ($salesmanRows as $row) {
                    $salesmanMap[$row['id']] = $row;
                }
            }

            foreach ($hasilRows as $index => $row) {
                $salesman = $salesmanMap[$row['salesman_id']] ?? [];
                $namaSalesman = $salesman['nama'] ?? 'Unknown';
                $kodeSalesman = $salesman['kode_alternatif'] ?? '-';
                $preferensi = (float) ($row['nilai_preferensi'] ?? 0);

                $results[] = [
                    'kode' => $kodeSalesman,
                    'nama' => $namaSalesman,
                    'preferensi' => $preferensi,
                    'ranking' => (int) ($row['ranking'] ?? ($index + 1)),
                ];

                $chartLabels[] = $namaSalesman;
                $chartValues[] = $preferensi;
                $chartHighlightColors[] = $index === 0 ? 'rgba(25, 135, 84, 0.85)' : 'rgba(13, 110, 253, 0.75)';
            }

            if (!empty($results)) {
                $topSalesmanSummary = $results[0];
            }

            $chartTitle = 'Grafik Nilai Preferensi Salesman - Periode ' . $selectedPeriode;

            $topPenilaian = $penilaianModel
                ->select('penilaian.nilai, penilaian.salesman_id, penilaian.kriteria_id')
                ->where('periode', $selectedPeriode)
                ->orderBy('nilai', 'DESC')
                ->first();

            if (!empty($topPenilaian)) {
                $salesman = $salesmanModel->find($topPenilaian['salesman_id']);
                $kriteria = $kriteriaModel->find($topPenilaian['kriteria_id']);

                $namaKriteria = $kriteria['nama_kriteria'] ?? '-';
                $nilaiTertinggi = $topPenilaian['nilai'] ?? '-';
                $namaSalesman = $salesman['nama'] ?? '-';

                $topCriteriaInsight = [
                    'nama_kriteria' => $namaKriteria,
                    'nilai_tertinggi' => $nilaiTertinggi,
                    'salesman' => $namaSalesman,
                    'deskripsi' => $namaSalesman . ' unggul pada indikator ' . $namaKriteria . ' dengan nilai tertinggi ' . $nilaiTertinggi . ' pada periode ' . $selectedPeriode . '.',
                ];
            }
        }

        return view('ceo/dashboard/index', [
            'title' => 'Dashboard CEO',
            'username' => session()->get('username') ?? 'CEO',
            'summary' => [
                'totalSalesman' => $totalSalesman,
                'totalKriteria' => $totalKriteria,
                'totalPeriode' => $totalPeriode,
                'totalHasilAkhir' => $totalHasilAkhir,
                'topSalesman' => $topSalesmanSummary['nama'] ?? '-',
                'topPreferensi' => $topSalesmanSummary['preferensi'] ?? 0,
            ],
            'results' => $results,
            'chart' => [
                'labels' => $chartLabels,
                'values' => $chartValues,
                'title' => $chartTitle,
                'colors' => $chartHighlightColors,
            ],
            'periodeOptions' => $periodeOptions,
            'selectedPeriode' => $selectedPeriode,
            'topSalesmanSummary' => $topSalesmanSummary,
            'topCriteriaInsight' => $topCriteriaInsight,
        ]);
    }

    public function laporan()
    {
        if ($redirect = $this->guardCeo()) {
            return $redirect;
        }

        $hasilModel = new HasilPerhitunganModel();
        $salesmanModel = new SalesmanModel();

        $periodeRows = $hasilModel
            ->select('periode')
            ->distinct()
            ->orderBy('periode', 'DESC')
            ->findAll();

        $periodeOptions = array_map(static function ($row) {
            return $row['periode'];
        }, $periodeRows);

        $selectedPeriode = $this->request->getGet('periode');

        if (empty($selectedPeriode) && !empty($periodeOptions)) {
            $selectedPeriode = $periodeOptions[0];
        }

        $hasilRows = [];
        if (!empty($selectedPeriode)) {
            $hasilRows = $hasilModel
                ->where('periode', $selectedPeriode)
                ->orderBy('ranking', 'ASC')
                ->findAll();
        }

        $salesmanIds = array_column($hasilRows, 'salesman_id');
        $salesmanMap = [];

        if (!empty($salesmanIds)) {
            $salesmanRows = $salesmanModel
                ->whereIn('id', $salesmanIds)
                ->findAll();

            foreach ($salesmanRows as $row) {
                $salesmanMap[$row['id']] = $row;
            }
        }

        $results = [];
        foreach ($hasilRows as $index => $row) {
            $salesman = $salesmanMap[$row['salesman_id']] ?? [];
            $results[] = [
                'ranking' => (int) ($row['ranking'] ?? ($index + 1)),
                'kode' => $salesman['kode_alternatif'] ?? '-',
                'nama' => $salesman['nama'] ?? 'Unknown',
                'preferensi' => (float) ($row['nilai_preferensi'] ?? 0),
            ];
        }

        $data = [
            'title' => 'Laporan CEO',
            'username' => session()->get('username') ?? 'CEO',
            'periode' => $selectedPeriode ?: '-',
            'periodeOptions' => $periodeOptions,
            'selectedPeriode' => $selectedPeriode,
            'results' => $results,
        ];

        return view('ceo/laporan/index', $data);
    }
}