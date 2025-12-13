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
                'branch_name' => 'Chakanok\'s - Davao City Center',
                'owner_name' => 'Pedro Santos',
                'location' => 'Davao City',
                'contact_number' => '+63 917 888 1234',
                'email' => 'pedro.santos@example.com',
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
                'branch_name' => 'Chakanok\'s - Tagum City',
                'owner_name' => 'Maria Lopez',
                'location' => 'Tagum, Davao del Norte',
                'contact_number' => '+63 918 555 6789',
                'email' => 'maria.lopez@example.com',
                'status' => 'Active',
                'monthly_sales' => 280000.00,
                'monthly_royalty' => 28000.00,
                'application_date' => '2024-02-10',
                'approval_date' => '2024-03-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'franchise_id' => 'FR-003',
                'branch_name' => 'Chakanok\'s - Digos City',
                'owner_name' => 'Jose Dela Cruz',
                'location' => 'Digos, Davao del Sur',
                'contact_number' => '+63 916 444 9999',
                'email' => 'jose.delacruz@example.com',
                'status' => 'Pending Approval',
                'monthly_sales' => null,
                'monthly_royalty' => null,
                'application_date' => '2024-11-01',
                'approval_date' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'franchise_id' => 'FR-004',
                'branch_name' => 'Chakanok\'s - Panabo City',
                'owner_name' => 'Anna Rivera',
                'location' => 'Panabo, Davao del Norte',
                'contact_number' => '+63 915 222 1111',
                'email' => 'anna.rivera@example.com',
                'status' => 'Active',
                'monthly_sales' => 310000.00,
                'monthly_royalty' => 31000.00,
                'application_date' => '2024-03-15',
                'approval_date' => '2024-04-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'franchise_id' => 'FR-005',
                'branch_name' => 'Chakanok\'s - Mati City',
                'owner_name' => 'Carlo Reyes',
                'location' => 'Mati, Davao Oriental',
                'contact_number' => '+63 926 333 5555',
                'email' => 'carlo.reyes@example.com',
                'status' => 'Application In Progress',
                'monthly_sales' => null,
                'monthly_royalty' => null,
                'application_date' => '2024-12-01',
                'approval_date' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $franchiseModel->insertBatch($franchises);
    }
}

