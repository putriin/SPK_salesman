<?php

namespace App\Controllers;

use App\Models\SalesmanModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;

class Penilaian extends BaseController
{
    protected $penilaianModel;
    protected $salesmanModel;
    protected $kriteriaModel;

    public function __construct()
    {
        $this->penilaianModel = new PenilaianModel();
        $this->salesmanModel = new SalesmanModel();
        $this->kriteriaModel = new KriteriaModel();
    }

    public function index()
    {
        $salesmen = $this->salesmanModel->orderBy('nama', 'ASC')->findAll() ?? [];

        $kriteria = $this->kriteriaModel
    ->select('id, nama_kriteria as nama, tipe as jenis, bobot')
    ->orderBy("
        CASE
            WHEN nama_kriteria = 'Kedisiplinan' THEN 1
            WHEN nama_kriteria = 'Close Order' THEN 2
            WHEN nama_kriteria = 'Tanggung Jawab' THEN 3
            WHEN nama_kriteria = 'Product Knowledge' THEN 4
            ELSE 99
        END
    ", '', false)
    ->findAll() ?? [];

        $editData = [];
        $editSalesmanId = $this->request->getGet('salesman_id');
        $editPeriode = $this->request->getGet('periode');

        if ($this->request->getGet('edit') && $editSalesmanId && $editPeriode) {
            $nilaiLama = $this->penilaianModel
                ->where('salesman_id', $editSalesmanId)
                ->where('periode', $editPeriode)
                ->where('deleted_at', null)
                ->findAll();

            foreach ($nilaiLama as $n) {
                $editData[$n['kriteria_id']] = $n['nilai'];
            }
        }

        $rekap = $this->penilaianModel
            ->select('penilaian.salesman_id, penilaian.periode, salesman.nama as salesman, COUNT(penilaian.kriteria_id) as total_kriteria')
            ->join('salesman', 'salesman.id = penilaian.salesman_id')
            ->where('penilaian.deleted_at', null)
            ->groupBy('penilaian.salesman_id, penilaian.periode, salesman.nama')
            ->orderBy('penilaian.periode', 'DESC')
            ->findAll() ?? [];

        return view('penilaian/index', [
            'salesmen' => $salesmen,
            'kriteria' => $kriteria,
            'rekap' => $rekap,
            'editData' => $editData,
            'editSalesmanId' => $editSalesmanId,
            'editPeriode' => $editPeriode,
        ]);
    }

    public function save()
    {
        $periode = $this->request->getPost('periode');
        $salesmanId = $this->request->getPost('salesman');
        $kriteriaIds = $this->request->getPost('kriteria_id');
        $nilai = $this->request->getPost('nilai');

        if (!$periode || !$salesmanId || empty($kriteriaIds) || empty($nilai)) {
            return redirect()->to('/penilaian')->with('error', 'Data penilaian belum lengkap.');
        }

        // Hapus data lama untuk salesman + periode yang sama.
        // Tujuannya agar saat edit, nilai lama diganti dengan nilai baru.
        $this->penilaianModel
            ->where('salesman_id', $salesmanId)
            ->where('periode', $periode)
            ->delete();

        foreach ($kriteriaIds as $index => $kriteriaId) {
            $this->penilaianModel->insert([
                'periode' => $periode,
                'salesman_id' => $salesmanId,
                'kriteria_id' => $kriteriaId,
                'nilai' => $nilai[$index],
            ]);
        }

        return redirect()->to('/penilaian')->with('success', 'Penilaian berhasil disimpan.');
    }

    public function delete($salesmanId = null, $periode = null)
    {
        if (!$salesmanId || !$periode) {
            return redirect()->to('/penilaian')->with('error', 'Data tidak valid.');
        }

        $this->penilaianModel
            ->where('salesman_id', $salesmanId)
            ->where('periode', $periode)
            ->delete();

        return redirect()->to('/penilaian')->with('success', 'Data berhasil dihapus.');
    }

    public function detail($salesmanId = null, $periode = null)
    {
        if (!$salesmanId || !$periode) {
            return redirect()->to('/penilaian')->with('error', 'Data tidak ditemukan.');
        }

        $rows = $this->penilaianModel
            ->select('
                penilaian.salesman_id,
                penilaian.periode,
                penilaian.nilai,
                salesman.nama as salesman,
                kriteria.nama_kriteria as kriteria,
                kriteria.tipe as jenis,
                kriteria.bobot
            ')
            ->join('salesman', 'salesman.id = penilaian.salesman_id')
            ->join('kriteria', 'kriteria.id = penilaian.kriteria_id')
            ->where('penilaian.salesman_id', $salesmanId)
            ->where('penilaian.periode', $periode)
            ->where('penilaian.deleted_at', null)
            ->orderBy('kriteria.id', 'ASC')
            ->findAll();

        if (empty($rows)) {
            return redirect()->to('/penilaian')->with('error', 'Detail penilaian tidak ditemukan.');
        }

        $detail = [
            'periode' => $rows[0]['periode'],
            'salesman_id' => $rows[0]['salesman_id'],
            'salesman' => $rows[0]['salesman'],
            'nilai' => [],
        ];

        foreach ($rows as $row) {
            $detail['nilai'][] = [
                'kriteria' => $row['kriteria'],
                'jenis' => $row['jenis'],
                'bobot' => $row['bobot'],
                'score' => $row['nilai'],
            ];
        }

        return view('penilaian/detail', compact('detail'));
    }
}