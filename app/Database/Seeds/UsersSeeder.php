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
                'email'         => 'branch1@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Branch Manager',
                'branch_id'     => 1,
                'supplier_id'   => null,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'inventory_staff1',
                'email'         => 'inventory1@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Inventory Staff',
                'branch_id'     => 1,
                'supplier_id'   => null,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'branch_manager2',
                'email'         => 'branch2@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Branch Manager',
                'branch_id'     => 2,
                'supplier_id'   => null,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'inventory_staff2',
                'email'         => 'inventory2@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Inventory Staff',
                'branch_id'     => 2,
                'supplier_id'   => null,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'central_admin',
                'email'         => 'admin@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Central Office Admin',
                'branch_id'     => null,
                'supplier_id'   => null,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'logistics_coordinator',
                'email'         => 'logistics@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Logistics Coordinator',
                'branch_id'     => null,
                'supplier_id'   => null,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'sysadmin',
                'email'         => 'sysadmin@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'System Administrator',
                'branch_id'     => null,
                'supplier_id'   => null,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'supplier1',
                'email'         => 'supplier1@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Supplier',
                'branch_id'     => null,
                'supplier_id'   => 1,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'supplier2',
                'email'         => 'supplier@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Supplier',
                'branch_id'     => null,
                'supplier_id'   => 2,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'username'      => 'supplier3',
                'email'         => 'supplier3@gmail.com',
                'password'      => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'Supplier',
                'branch_id'     => null,
                'supplier_id'   => 3,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ]
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
 