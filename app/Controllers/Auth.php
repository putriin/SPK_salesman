<?php

namespace App\Controllers;

use App\Models\UserModel;
use Google\Client as GoogleClient;

class Auth extends BaseController
{
    public function login()
    {
        return view('login', ['title' => 'SPK Salesman Hamasa - Login']);
    }

    public function signup()
    {
        return view('signup', ['title' => 'Signup']);
    }

    public function attemptSignup()
    {
        $rules = [
            'email'    => 'required|valid_email|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]',
            'password' => 'required|min_length[6]|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $email    = trim($this->request->getPost('email'));
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()
                ->with('errors', ['email' => 'Email sudah dipakai.']);
        }

        if ($userModel->where('username', $username)->first()) {
            return redirect()->back()->withInput()
                ->with('errors', ['username' => 'Username sudah dipakai.']);
        }

        $userModel->insert([
            'email'         => $email,
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => 'manajer',
            'auth_provider' => 'manual',
            'is_active'     => 1,
        ]);

        return redirect()->to('/signup')
            ->with('success', 'Akun berhasil dibuat.');
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $username  = trim($this->request->getPost('username'));
        $password  = $this->request->getPost('password');

        $user = $userModel->findActiveByUsername($username);

        if (!$user) {
            return redirect()->back()->withInput()
                ->with('errors', ['username' => 'Username tidak ditemukan atau akun tidak aktif.']);
        }

        if (empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()
                ->with('errors', ['password' => 'Password salah.']);
        }

        $userModel->touchLastLogin((int) $user['id']);

        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        session()->regenerate();

        return $this->redirectByRole($user['role']);
    }

    public function googleLogin()
    {
        $credential = $this->request->getPost('credential');

        if (!$credential) {
            return redirect()->to('/login')->with('errors', [
                'login' => 'Credential Google tidak ditemukan.',
            ]);
        }

        $clientId = env('google.clientId');
        if (!$clientId) {
            return redirect()->to('/login')->with('errors', [
                'login' => 'Google Client ID belum diatur di file .env.',
            ]);
        }

        $client = new GoogleClient([
            'client_id' => $clientId,
        ]);

        $payload = $client->verifyIdToken($credential);

        if (!$payload) {
            return redirect()->to('/login')->with('errors', [
                'login' => 'Token Google tidak valid.',
            ]);
        }

        $email         = $payload['email'] ?? null;
        $emailVerified = $payload['email_verified'] ?? false;

        if (!$email || !$emailVerified) {
            return redirect()->to('/login')->with('errors', [
                'login' => 'Email Google tidak valid atau belum terverifikasi.',
            ]);
        }

        $userModel = new UserModel();
        $user = $userModel->findActiveByEmail($email);

        if (!$user) {
            return redirect()->to('/login')->with('errors', [
                'login' => 'Email Google Anda belum didaftarkan oleh admin.',
            ]);
        }

        $userModel->updateGoogleProfile((int) $user['id'], [
            'google_sub'        => $payload['sub'] ?? null,
            'full_name'         => $payload['name'] ?? ($user['full_name'] ?? null),
            'avatar_url'        => $payload['picture'] ?? null,
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        session()->regenerate();

        return $this->redirectByRole($user['role']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    private function redirectByRole(string $role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'manajer':
                return redirect()->to('/dashboard');
            case 'ceo':
                return redirect()->to('/ceo/dashboard');
            default:
                session()->destroy();
                return redirect()->to('/login')->with('errors', [
                    'login' => 'Role user tidak diizinkan.',
                ]);
        }
    }
}