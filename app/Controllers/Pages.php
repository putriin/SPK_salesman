<?php

namespace App\Controllers;

class Pages extends BaseController
{
    private function guard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        return null;
    }

    public function salesman()
    {
        if ($redir = $this->guard()) return $redir;
        return view('pages/salesman', ['title' => 'Data Salesman', 'username' => session()->get('username') ?? 'Admin']);
    }

    public function kriteria()
    {
        if ($redir = $this->guard()) return $redir;
        return view('pages/kriteria', ['title' => 'Data Kriteria', 'username' => session()->get('username') ?? 'Admin']);
    }

    public function penilaian()
    {
        if ($redir = $this->guard()) return $redir;
        return view('pages/penilaian', ['title' => 'Data Penilaian', 'username' => session()->get('username') ?? 'Admin']);
    }

    public function perhitungan()
    {
        if ($redir = $this->guard()) return $redir;
        return view('pages/perhitungan', ['title' => 'Proses Perhitungan', 'username' => session()->get('username') ?? 'Admin']);
    }

    public function cetak()
    {
        if ($redir = $this->guard()) return $redir;
        return view('pages/cetak', ['title' => 'Cetak', 'username' => session()->get('username') ?? 'Admin']);
    }
}