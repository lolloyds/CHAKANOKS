<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $suppliers = [
            [
                'supplier_name' => 'Fresh Poultry Farm',
                'contact_person' => 'Juan Dela Cruz',
                'phone' => '+63 917 123 4567',
                'email' => 'juan.delacruz@freshpoultry.com',
                'address' => '123 Poultry Road, Davao City',
                'supply_type' => 'Whole Chickens',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'supplier_name' => 'Spice Masters Trading',
                'contact_person' => 'Maria Santos',
                'phone' => '+63 918 234 5678',
                'email' => 'maria.santos@spicemasters.com',
                'address' => '456 Spice Avenue, Davao City',
                'supply_type' => 'Spices and Seasonings',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Insert suppliers
        $db->table('suppliers')->insertBatch($suppliers);
    }
}

