<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $admin = [
            'google_id'      => null,
            'email'          => 'admin@carin.local',
            'password_hash'  => password_hash('admin123', PASSWORD_DEFAULT),
            'name'           => 'Administrator',
            'avatar_url'     => null,
            'phone'          => '081234567890',
            'role'           => 'admin',
            'status'         => 'active',
            'free_slot_used' => 0,
            'created_at'     => $now,
            'updated_at'     => $now,
        ];

        // Upsert by email (idempotent)
        $existing = $this->db->table('users')->where('email', $admin['email'])->get()->getRow();
        if (! $existing) {
            $this->db->table('users')->insert($admin);
        }
    }
}
