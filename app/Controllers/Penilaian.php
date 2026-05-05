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
        $salesmen = $this->salesmanModel->orderBy('nama','ASC')->findAll() ?? [];

        // Alias kriteria sesuai database
        $kriteria = $this->kriteriaModel
            ->select('id, nama_kriteria as nama, tipe as jenis, bobot')
            ->orderBy('id','ASC')
            ->findAll() ?? [];

        // Rekap penilaian per salesman per periode
        $rekap = $this->penilaianModel
            ->select('penilaian.salesman_id, penilaian.periode, salesman.nama as salesman, COUNT(penilaian.kriteria_id) as total_kriteria')
            ->where('deleted_at', null)
            ->join('salesman','salesman.id = penilaian.salesman_id')
            ->groupBy('penilaian.salesman_id, penilaian.periode, salesman.nama')
            ->findAll() ?? [];

        return view('penilaian/index', compact('salesmen','kriteria','rekap'));
    }

    public function save()
    {
        $data = $this->request->getPost();
        // implementasi simpan/update sesuai kebutuhan
    }

    public function delete($salesmanId = null, $periode = null)
    {
        if (!$salesmanId || !$periode) {
            return redirect()->to('/penilaian')->with('error','Data tidak valid.');
        }

        $this->penilaianModel
            ->where('salesman_id', $salesmanId)
            ->where('periode', $periode)
            ->delete(); // soft delete

        return redirect()->to('/penilaian')->with('success','Data berhasil dihapus.');
    }

    public function detail($salesmanId = null, $periode = null)
    {
        if (!$salesmanId || !$periode) {
            return redirect()->to('/penilaian')->with('error','Data tidak ditemukan.');
        }

        $detail = $this->penilaianModel
            ->where('salesman_id',$salesmanId)
            ->where('periode',$periode)
            ->where('deleted_at', null)
            ->findAll();

        return view('penilaian/detail', compact('detail'));
    }
}