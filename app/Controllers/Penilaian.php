<?php

namespace App\Controllers;

use App\Models\SalesmanModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;

class Penilaian extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $salesmanModel  = new SalesmanModel();
        $kriteriaModel  = new KriteriaModel();
        $penilaianModel = new PenilaianModel();

        $salesmen = $salesmanModel->orderBy('nama', 'ASC')->findAll();
        $kriteriaRaw = $kriteriaModel->orderBy('id', 'ASC')->findAll();

        $kriteria = [];
        foreach ($kriteriaRaw as $k) {
            $kriteria[] = [
                'id'    => $k['id'],
                'nama'  => $k['nama_kriteria'],
                'jenis' => ucfirst($k['tipe']),
                'bobot' => $k['bobot'],
            ];
        }

        $totalKriteria = count($kriteriaRaw);

        // Rekap detail tetap dipakai untuk edit/detail per salesman
        $rekap = $penilaianModel
            ->select('penilaian.salesman_id, penilaian.periode, salesman.nama, COUNT(penilaian.kriteria_id) as total_kriteria')
            ->join('salesman', 'salesman.id = penilaian.salesman_id')
            ->groupBy('penilaian.salesman_id, penilaian.periode, salesman.nama')
            ->orderBy('penilaian.periode', 'DESC')
            ->orderBy('salesman.nama', 'ASC')
            ->findAll();

        foreach ($rekap as $i => $r) {
            $rekap[$i]['salesman']    = $r['nama'];
            $rekap[$i]['salesman_id'] = $r['salesman_id'];
            $rekap[$i]['periode']     = $r['periode'];
            $rekap[$i]['status']      = ((int) $r['total_kriteria'] >= $totalKriteria) ? 'Lengkap' : 'Belum Lengkap';
        }

        // Rekap PER PERIODE, dibandingkan dengan SEMUA salesman master
        $periodeRows = $penilaianModel
            ->select('periode')
            ->distinct()
            ->orderBy('periode', 'DESC')
            ->findAll();

        $rekapPeriode = [];
        $no = 1;

        foreach ($periodeRows as $periodeRow) {
            $periode = $periodeRow['periode'];

            // Ambil jumlah kriteria yang sudah dinilai per salesman pada periode ini
            $periodeRekap = $penilaianModel
                ->select('salesman_id, COUNT(kriteria_id) as total_kriteria')
                ->where('periode', $periode)
                ->groupBy('salesman_id')
                ->findAll();

            // Map jumlah penilaian per salesman
            $salesmanNilaiMap = [];
            foreach ($periodeRekap as $item) {
                $salesmanNilaiMap[$item['salesman_id']] = (int) $item['total_kriteria'];
            }

            $salesmanLengkap = 0;
            $belumLengkap = 0;

            foreach ($salesmen as $salesman) {
                $jumlahNilai = $salesmanNilaiMap[$salesman['id']] ?? 0;

                if ($jumlahNilai >= $totalKriteria) {
                    $salesmanLengkap++;
                } else {
                    $belumLengkap++;
                }
            }

            $rekapPeriode[] = [
                'no'               => $no++,
                'periode'          => $periode,
                'salesman_lengkap' => $salesmanLengkap,
                'belum_lengkap'    => $belumLengkap,
                'total_salesman'   => count($salesmen),
            ];
        }

        // MODE EDIT
        $editMode   = $this->request->getGet('edit');
        $salesmanId = $this->request->getGet('salesman_id');
        $periode    = $this->request->getGet('periode');

        $formData = [
            'periode'     => '',
            'salesman_id' => '',
            'nilai'       => [],
        ];

        if ($editMode && $salesmanId && $periode) {
            $existing = $penilaianModel
                ->where('salesman_id', $salesmanId)
                ->where('periode', $periode)
                ->findAll();

            if (!empty($existing)) {
                $formData['periode']     = $periode;
                $formData['salesman_id'] = $salesmanId;

                foreach ($existing as $item) {
                    $formData['nilai'][$item['kriteria_id']] = $item['nilai'];
                }
            }
        }

        return view('penilaian/index', [
            'salesmen'      => $salesmen,
            'kriteria'      => $kriteria,
            'rekap'         => $rekap,
            'rekapPeriode'  => $rekapPeriode,
            'formData'      => $formData,
            'editMode'      => $editMode ? true : false,
            'totalKriteria' => $totalKriteria,
        ]);
    }

    public function save()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $penilaianModel = new PenilaianModel();
        $db = \Config\Database::connect();

        $editMode   = $this->request->getPost('edit_mode') === '1';
        $periode    = trim((string) $this->request->getPost('periode'));
        $salesmanId = (int) $this->request->getPost('salesman');
        $kriteriaId = $this->request->getPost('kriteria_id') ?? [];
        $nilai      = $this->request->getPost('nilai') ?? [];

        if ($periode === '' || $salesmanId <= 0 || empty($kriteriaId) || empty($nilai) || count($kriteriaId) !== count($nilai)) {
            return redirect()->back()->withInput()->with('error', 'Data penilaian tidak lengkap.');
        }

        $rows = [];
        foreach ($kriteriaId as $index => $kritId) {
            $score = is_numeric($nilai[$index] ?? null) ? (float) $nilai[$index] : null;

            if ($score === null || $score < 0 || $score > 100) {
                return redirect()->back()->withInput()->with('error', 'Nilai harus angka antara 0 sampai 100.');
            }

            $rows[] = [
                'periode'     => $periode,
                'salesman_id' => $salesmanId,
                'kriteria_id' => (int) $kritId,
                'nilai'       => $score,
            ];
        }

        $db->transStart();

        $penilaianModel
            ->where('salesman_id', $salesmanId)
            ->where('periode', $periode)
            ->delete();

        foreach ($rows as $row) {
            $penilaianModel->insert($row);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan perubahan penilaian.');
        }

        $message = $editMode
            ? 'Penilaian berhasil diperbarui.'
            : 'Penilaian berhasil disimpan.';

        return redirect()
            ->to(base_url('penilaian/detail/' . $salesmanId . '/' . $periode))
            ->with('success', $message);
    }

    public function detail($salesmanId = null, $periode = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (!$salesmanId || !$periode) {
            return redirect()->to('/penilaian');
        }

        $penilaianModel = new PenilaianModel();

        $rows = $penilaianModel
            ->select('penilaian.*, salesman.nama as salesman_nama, kriteria.nama_kriteria, kriteria.tipe, kriteria.bobot')
            ->join('salesman', 'salesman.id = penilaian.salesman_id')
            ->join('kriteria', 'kriteria.id = penilaian.kriteria_id')
            ->where('penilaian.salesman_id', $salesmanId)
            ->where('penilaian.periode', $periode)
            ->orderBy('kriteria.id', 'ASC')
            ->findAll();

        if (empty($rows)) {
            return redirect()->to('/penilaian')
                ->with('errors', ['detail' => 'Data detail penilaian tidak ditemukan.']);
        }

        $detail = [
            'id'          => $salesmanId . '/' . $periode,
            'salesman_id' => $salesmanId,
            'periode'     => $periode,
            'salesman'    => $rows[0]['salesman_nama'],
            'nilai'       => [],
        ];

        foreach ($rows as $row) {
            $detail['nilai'][] = [
                'kriteria' => $row['nama_kriteria'],
                'jenis'    => ucfirst($row['tipe']),
                'bobot'    => $row['bobot'],
                'score'    => $row['nilai'],
            ];
        }

        return view('penilaian/detail', [
            'detail' => $detail,
        ]);
    }
}