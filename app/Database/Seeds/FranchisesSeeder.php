<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FranchisesSeeder extends Seeder
{
    public function run()
    {
        $franchiseModel = new \App\Models\FranchiseModel();
        
        $franchises = [
            [
                'franchise_id' => 'FR-001',
                'branch_name' => 'Davao Branch 1',
                'owner_name' => 'Erjay C. Dosdos',
                'location' => '123 Rizal St, Davao City',
                'contact_number' => '+63 917 888 1234',
                'email' => 'erjaydosdos@example.com',
                'status' => 'Active',
                'monthly_sales' => 350000.00,
                'monthly_royalty' => 35000.00,
                'application_date' => '2024-01-15',
                'approval_date' => '2024-02-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'franchise_id' => 'FR-002',
                'branch_name' => 'Davao Branch 2',
                'owner_name' => 'Lemuel Gold',
                'location' => '45 Mabini St, Davao City',
                'contact_number' => '+63 918 555 6789',
                'email' => 'lemuel@example.com',
                'status' => 'Active',
                'monthly_sales' => 280000.00,
                'monthly_royalty' => 28000.00,
                'application_date' => '2024-02-10',
                'approval_date' => '2024-03-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $franchiseModel->insertBatch($franchises);
    }
}
