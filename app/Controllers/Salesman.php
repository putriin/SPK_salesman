<?php

namespace App\Controllers;

use App\Models\SalesmanModel;
use App\Models\PenilaianModel;

class Salesman extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $salesmanModel = new SalesmanModel();
        $salesmen = $salesmanModel->orderBy('id', 'ASC')->findAll();

        $rows = [];
        foreach ($salesmen as $s) {
            $rows[] = [
                'id'     => $s['id'],
                'kode'   => $s['kode_alternatif'],
                'nama'   => $s['nama'],
                'gender' => $s['gender'],
                'alamat' => $s['alamat'],
            ];
        }

        return view('salesman/index', [
            'title'    => 'Alternatif Data',
            'username' => session()->get('username') ?? 'Admin',
            'rows'     => $rows,
        ]);
    }

    public function save()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $salesmanModel = new SalesmanModel();
        $id = (int) $this->request->getPost('id');

        $data = [
            'kode_alternatif' => trim((string) $this->request->getPost('kode_alternatif')),
            'nama'            => trim((string) $this->request->getPost('nama')),
            'gender'          => trim((string) $this->request->getPost('gender')),
            'alamat'          => trim((string) $this->request->getPost('alamat')),
        ];

        if (
            $data['kode_alternatif'] === '' ||
            $data['nama'] === '' ||
            $data['gender'] === '' ||
            $data['alamat'] === ''
        ) {
            return redirect()->back()->withInput()->with('error', 'Semua field salesman wajib diisi.');
        }

        if ($id > 0) {
            $salesmanModel->update($id, $data);
            $message = 'Data salesman berhasil diperbarui.';
        } else {
            $salesmanModel->insert($data);
            $message = 'Data salesman berhasil ditambahkan.';
        }

        return redirect()->to('/salesman')->with('success', $message);
    }

    public function delete($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $salesmanModel = new SalesmanModel();
        $penilaianModel = new PenilaianModel();

        $dipakai = $penilaianModel->where('salesman_id', $id)->countAllResults();

        if ($dipakai > 0) {
            return redirect()->to('/salesman')
                ->with('error', 'Data salesman tidak bisa dihapus karena sudah dipakai di penilaian.');
        }

        $salesmanModel->delete($id);

        return redirect()->to('/salesman')->with('success', 'Data salesman berhasil dihapus.');
    }
}