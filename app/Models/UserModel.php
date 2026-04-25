<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    protected $allowedFields = [
        'username',
        'email',
        'full_name',
        'password_hash',
        'role',
        'google_sub',
        'avatar_url',
        'auth_provider',
        'is_active',
        'email_verified_at',
        'last_login_at',
    ];

    protected $validationRules = [
        'username' => 'permit_empty|min_length[3]|max_length[50]',
        'email'    => 'permit_empty|valid_email|max_length[191]',
        'role'     => 'permit_empty|in_list[admin,manajer,ceo]',
    ];

    protected $validationMessages = [
        'username' => [
            'min_length' => 'Username minimal 3 karakter.',
            'max_length' => 'Username maksimal 50 karakter.',
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid.',
            'max_length'  => 'Email terlalu panjang.',
        ],
        'role' => [
            'in_list' => 'Role tidak valid.',
        ],
    ];

   /* Cari user aktif berdasarkan username */
    public function findActiveByUsername(string $username): ?array
    {
        return $this->where('username', $username)
            ->where('is_active', 1)
            ->first();
    }

   /* Cari user aktif berdasarkan email */
    public function findActiveByEmail(string $email): ?array
    {
        return $this->where('email', $email)
            ->where('is_active', 1)
            ->first();
    }

   /* Simpan data Google ke user yang sudah terdaftar */
    public function updateGoogleProfile(int $userId, array $googleData): bool
    {
        $data = [
            'google_sub'        => $googleData['google_sub'] ?? null,
            'full_name'         => $googleData['full_name'] ?? null,
            'avatar_url'        => $googleData['avatar_url'] ?? null,
            'email_verified_at' => $googleData['email_verified_at'] ?? date('Y-m-d H:i:s'),
            'last_login_at'     => date('Y-m-d H:i:s'),
        ];

        return $this->update($userId, $data);
    }

    /* Update waktu login terakhir user */
    public function touchLastLogin(int $userId): bool
    {
        return $this->update($userId, [
            'last_login_at' => date('Y-m-d H:i:s'),
        ]);
    }
}