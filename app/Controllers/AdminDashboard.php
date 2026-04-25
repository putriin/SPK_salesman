<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminDashboard extends BaseController
{
    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();

        $data = [
            'title'          => 'Dashboard Admin',
            'username'       => session()->get('username'),
            'totalUser'      => $userModel->countAllResults(),
            'totalAdmin'     => $userModel->where('role', 'admin')->countAllResults(),
            'totalManajer'   => $userModel->where('role', 'manajer')->countAllResults(),
            'totalCeo'       => $userModel->where('role', 'ceo')->countAllResults(),
        ];

        return view('admin/dashboard/index', $data);
    }
}