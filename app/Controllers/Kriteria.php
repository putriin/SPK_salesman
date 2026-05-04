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

        // Total bobot input, dipakai untuk menampilkan bobot normalisasi
        $totalBobot = array_sum(array_map(static function ($item) {
            return (float) ($item['bobot'] ?? 0);
        }, $kriteria));

        foreach ($kriteria as $item) {
            $nama = $item['nama_kriteria'] ?? '';
            $tipe = $item['tipe'] ?? '';

            // Bobot asli/input
            $bobotRaw = (float) ($item['bobot'] ?? 0);
            $bobot = $this->formatBobot($bobotRaw);

            // Bobot normalisasi agar total bobot menjadi 1
            $bobotNormalisasi = $totalBobot > 0
                ? $this->formatBobot($bobotRaw / $totalBobot)
                : '0';

            $rows[] = [
                'no' => $no++,
                'id' => $item['id'],

                // field utama
                'nama_kriteria'       => $nama,
                'tipe'                => $tipe,
                'bobot'               => $bobot,
                'bobot_normalisasi'   => $bobotNormalisasi,

                // alias tambahan
                'nama'                => $nama,
                'name'                => $nama,
                'jenis'               => $tipe,
                'type'                => $tipe,
                'weight'              => $bobot,
                'normalized_weight'   => $bobotNormalisasi,
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

        return is_numeric($value) && (float) $value > 0;
    }

    private function formatBobot($value): string
    {
        $value = (float) $value;
        $formatted = rtrim(rtrim(number_format($value, 9, '.', ''), '0'), '.');

        return $formatted === '' ? '0' : $formatted;
    }

    private function generateKodeKriteria(): string
    {
        $last = $this->kriteriaModel
            ->select('kode_kriteria')
            ->where('kode_kriteria IS NOT NULL')
            ->where('kode_kriteria !=', '')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$last || empty($last['kode_kriteria'])) {
            return 'C1';
        }

        $lastNumber = (int) preg_replace('/[^0-9]/', '', $last['kode_kriteria']);
        $nextNumber = $lastNumber + 1;

        return 'C' . $nextNumber;
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
            return redirect()->back()->withInput()->with('error', 'Bobot harus berupa angka positif. Contoh: 1, 3, atau 5.');
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

        // Kode kriteria dibuat otomatis hanya saat tambah data baru
        $data['kode_kriteria'] = $this->generateKodeKriteria();

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