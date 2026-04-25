<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Cegah dobel insert admin
        $exists = $this->db->table('users')
            ->where('username', 'admin')
            ->get()
            ->getRow();

        if ($exists) {
            echo "Admin sudah ada.\n";
            return;
        }

        $data = [
            'username'      => 'admin',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'role'          => 'admin', // pastikan kolom role sudah ada
        ];

        $this->db->table('users')->insert($data);
    }
}