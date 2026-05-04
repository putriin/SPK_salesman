<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminUsers extends BaseController
{
    protected function checkUser()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $allowedRoles = ['admin', 'manajer', 'ceo'];

        if (!in_array(session()->get('role'), $allowedRoles, true)) {
            return redirect()->to('/login');
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkUser()) {
            return $redirect;
        }

        $userModel = new UserModel();

        $data = [
            'title'    => 'Pengaturan User',
            'username' => session()->get('username'),
            'users'    => $userModel->orderBy('id', 'DESC')->findAll(),
            'filter'   => 'all',
        ];

        return view('admin/users/index', $data);
    }

    public function role($role = null)
    {
        if ($redirect = $this->checkUser()) {
            return $redirect;
        }

        $allowedRoles = ['admin', 'manajer', 'ceo'];

        if (!in_array($role, $allowedRoles, true)) {
            return redirect()->to('/pengaturan-user');
        }

        $userModel = new UserModel();

        $data = [
            'title'    => 'Pengaturan User ' . ucfirst($role),
            'username' => session()->get('username'),
            'users'    => $userModel->where('role', $role)->orderBy('id', 'DESC')->findAll(),
            'filter'   => $role,
        ];

        return view('admin/users/index', $data);
    }

    public function store()
    {
        if ($redirect = $this->checkUser()) {
            return $redirect;
        }

        $rules = [
            'username'  => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'     => 'required|valid_email|max_length[191]|is_unique[users.email]',
            'full_name' => 'permit_empty|max_length[100]',
            'password'  => 'required|min_length[6]',
            'role'      => 'required|in_list[admin,manajer,ceo]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $userModel->insert([
            'username'      => trim($this->request->getPost('username')),
            'email'         => trim($this->request->getPost('email')),
            'full_name'     => trim((string) $this->request->getPost('full_name')),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
            'auth_provider' => 'manual',
            'is_active'     => 1,
        ]);

        return redirect()->to('/pengaturan-user')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function updateRole($id)
    {
        if ($redirect = $this->checkUser()) {
            return $redirect;
        }

        $rules = [
            'role' => 'required|in_list[admin,manajer,ceo]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/pengaturan-user')
                ->with('errors', ['user' => 'User tidak ditemukan.']);
        }

        $userModel->update($id, [
            'role' => $this->request->getPost('role'),
        ]);

        return redirect()->to('/pengaturan-user')
            ->with('success', 'Role user berhasil diperbarui.');
    }

    public function resetPassword($id)
    {
        if ($redirect = $this->checkUser()) {
            return $redirect;
        }

        $rules = [
            'new_password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/pengaturan-user')
                ->with('errors', ['user' => 'User tidak ditemukan.']);
        }

        $userModel->update($id, [
            'password_hash' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/pengaturan-user')
            ->with('success', 'Password user berhasil direset.');
    }

    public function delete($id)
    {
        if ($redirect = $this->checkUser()) {
            return $redirect;
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/pengaturan-user')
                ->with('errors', ['user' => 'User tidak ditemukan.']);
        }

        if ((int) $user['id'] === (int) session()->get('user_id')) {
            return redirect()->to('/pengaturan-user')
                ->with('errors', [
                    'user' => 'Akun yang sedang login tidak bisa dihapus.',
                ]);
        }

        $userModel->delete($id);

        return redirect()->to('/pengaturan-user')
            ->with('success', 'User berhasil dihapus.');
    }
}