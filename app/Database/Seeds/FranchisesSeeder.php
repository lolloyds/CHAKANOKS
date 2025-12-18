<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class FranchisesSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now()->toDateTimeString();

        $franchises = [
            [
                'franchise_name' => 'CHAKANOKS Davao North',
                'owner_name' => 'Maria Santos',
                'contact_person' => 'Maria Santos',
                'phone' => '+63 917 123 4567',
                'email' => 'maria.santos@chakanoks.com',
                'address' => '123 Maharlika Highway, Davao City',
                'status' => 'Active',
                'established_date' => '2023-01-15',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'franchise_name' => 'CHAKANOKS Davao South',
                'owner_name' => 'Juan Dela Cruz',
                'contact_person' => 'Juan Dela Cruz',
                'phone' => '+63 918 234 5678',
                'email' => 'juan.delacruz@chakanoks.com',
                'address' => '456 McArthur Highway, Davao City',
                'status' => 'Active',
                'established_date' => '2023-03-20',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('franchises')->insertBatch($franchises);
    }
}