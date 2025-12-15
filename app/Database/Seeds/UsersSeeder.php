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
                'username'      => 'branch_manager1',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Branch Manager',
                'branch_id'     => 1,
                'supplier_id'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'inventory_staff1',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Inventory Staff',
                'branch_id'     => 1,
                'supplier_id'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
                        [
                'username'      => 'branch_manager2',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Branch Manager',
                'branch_id'     => 2,
                'supplier_id'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'inventory_staff2',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Inventory Staff',
                'branch_id'     => 2,
                'supplier_id'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'central_admin',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Central Office Admin',
                'branch_id'     => null,
                'supplier_id'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'logistics_coordinator',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Logistics Coordinator',
                'branch_id'     => null,
                'supplier_id'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'franchise_manager',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Franchise Manager',
                'branch_id'     => null,
                'supplier_id'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'sysadmin',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'System Administrator',
                'branch_id'     => null,
                'supplier_id'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'supplier1',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Supplier',
                'branch_id'     => null,
                'supplier_id'   => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
