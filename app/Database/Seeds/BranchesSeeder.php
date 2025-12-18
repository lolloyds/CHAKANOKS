<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class BranchesSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now()->toDateTimeString();

        $branches = [
            [
                'franchise_id'   => 1, // CHAKANOKS Davao North
                'name'           => 'Davao Branch 1',
                'address'        => '123 Rizal St, Davao City',
                'phone'          => '+63 82 123 4567',
                'status'         => 'active',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'franchise_id'   => 2, // CHAKANOKS Davao South
                'name'           => 'Davao Branch 2',
                'address'        => '45 Mabini St, Davao City',
                'phone'          => '+63 82 234 5678',
                'status'         => 'active',
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ];

        $this->db->table('branches')->insertBatch($branches);
    }
}
