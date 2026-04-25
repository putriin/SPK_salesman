<?php

namespace App\Controllers;

use App\Models\KriteriaModel;

class Kriteria extends BaseController
{
    protected $kriteriaModel;

    public function __construct()
    {
        $this->kriteriaModel = new KriteriaModel();
    }

    public function index()
    {
        $kriteria = $this->kriteriaModel->orderBy('id', 'ASC')->findAll();

        $rows = [];
        $no = 1;

        foreach ($kriteria as $item) {
            $nama = $item['nama_kriteria'] ?? '';
            $tipe = $item['tipe'] ?? '';
            $bobot = $this->formatBobot($item['bobot'] ?? 0);

            $rows[] = [
                'no' => $no++,
                'id' => $item['id'],

                // field utama
                'nama_kriteria' => $nama,
                'tipe'          => $tipe,
                'bobot'         => $bobot,

                // alias tambahan biar cocok dengan JS lama/baru
                'nama'          => $nama,
                'name'          => $nama,
                'jenis'         => $tipe,
                'type'          => $tipe,
                'weight'        => $bobot,
            ];
        }

        $data = [
            'title' => 'Data Kriteria',
            'rows'  => $rows,
        ];

        return view('kriteria/index', $data);
    }

    private function normalizeBobot($value): float
    {
        $value = trim((string) $value);
        $value = str_replace(',', '.', $value);
        return (float) $value;
    }

    private function isValidBobot($value): bool
    {
        $value = trim((string) $value);
        $value = str_replace(',', '.', $value);

        return is_numeric($value);
    }

    private function formatBobot($value): string
    {
        $value = (float) $value;
        $formatted = rtrim(rtrim(number_format($value, 9, '.', ''), '0'), '.');

        return $formatted === '' ? '0' : $formatted;
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $namaKriteria = trim((string) $this->request->getPost('nama_kriteria'));
        $tipe = strtolower(trim((string) $this->request->getPost('tipe')));
        $bobotInput = $this->request->getPost('bobot');

        if ($namaKriteria === '') {
            return redirect()->back()->withInput()->with('error', 'Nama kriteria wajib diisi.');
        }

        if (!in_array($tipe, ['benefit', 'cost'], true)) {
            return redirect()->back()->withInput()->with('error', 'Tipe kriteria harus benefit atau cost.');
        }

        if (!$this->isValidBobot($bobotInput)) {
            return redirect()->back()->withInput()->with('error', 'Bobot harus berupa angka yang valid.');
        }

        $bobot = $this->normalizeBobot($bobotInput);

        $data = [
            'nama_kriteria' => $namaKriteria,
            'tipe'          => $tipe,
            'bobot'         => $bobot,
        ];

        if (!empty($id)) {
            $existing = $this->kriteriaModel->find($id);

            if (!$existing) {
                return redirect()->to('/kriteria')->with('error', 'Data kriteria tidak ditemukan.');
            }

            $this->kriteriaModel->update($id, $data);

            return redirect()->to('/kriteria')->with('success', 'Data kriteria berhasil diperbarui.');
        }

        $this->kriteriaModel->insert($data);

        return redirect()->to('/kriteria')->with('success', 'Data kriteria berhasil ditambahkan.');
    }

    public function store()
    {
        return $this->save();
    }

    public function update($id = null)
    {
        if ($id !== null && !$this->request->getPost('id')) {
            $_POST['id'] = $id;
        }

        return $this->save();
    }

    public function delete($id)
    {
        $existing = $this->kriteriaModel->find($id);

        if (!$existing) {
            return redirect()->to('/kriteria')->with('error', 'Data kriteria tidak ditemukan.');
        }

        $this->kriteriaModel->delete($id);

        return redirect()->to('/kriteria')->with('success', 'Data kriteria berhasil dihapus.');
    }
}