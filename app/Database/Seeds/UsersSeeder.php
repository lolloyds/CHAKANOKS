<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now()->toDateTimeString();

        $users = [
            [
                'username'      => 'branch_manager',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Branch Manager',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'inventory_staff',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Inventory Staff',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'central_admin',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Central Office Admin',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'supplier_user',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Supplier',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'logistics_user',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Logistics',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'coordinator_user',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Coordinator',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'franchise_manager',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Franchise Manager',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'sysadmin',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'System Administrator',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
