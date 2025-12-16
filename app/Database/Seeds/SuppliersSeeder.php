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
                'supplier_name' => 'Fresh Poultry Trading',
                'contact_person' => 'Virgilio',
                'phone' => '+63 917 123 4567',
                'email' => 'virgilio@freshpoultry.com',
                'address' => '123 Poultry Road, Davao City',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'supplier_name' => 'Edriane Farm',
                'contact_person' => 'Edriane',
                'phone' => '+63 918 234 5678',
                'email' => 'edriane@edrianefarm.com',
                'address' => '456 Spice Avenue, Davao City',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert suppliers
        $db->table('suppliers')->insertBatch($suppliers);
    }
}

