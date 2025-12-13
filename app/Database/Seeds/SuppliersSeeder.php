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
            ],
            [
                'supplier_name' => 'Packaging Solutions Inc.',
                'contact_person' => 'Pedro Garcia',
                'phone' => '+63 919 345 6789',
                'email' => 'pedro.garcia@packaging.com',
                'address' => '789 Packaging Street, Davao City',
                'supply_type' => 'Packaging Materials',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'supplier_name' => 'Beverage Distributors Co.',
                'contact_person' => 'Ana Rodriguez',
                'phone' => '+63 920 456 7890',
                'email' => 'ana.rodriguez@beverage.com',
                'address' => '321 Beverage Boulevard, Davao City',
                'supply_type' => 'Beverages',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'supplier_name' => 'Gas & Fuel Supply',
                'contact_person' => 'Carlos Mendoza',
                'phone' => '+63 921 567 8901',
                'email' => 'carlos.mendoza@gasfuel.com',
                'address' => '654 Fuel Road, Davao City',
                'supply_type' => 'Cooking Fuel',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'supplier_name' => 'Premium Chicken Supplier',
                'contact_person' => 'Luis Fernandez',
                'phone' => '+63 922 678 9012',
                'email' => 'luis.fernandez@premiumchicken.com',
                'address' => '987 Chicken Lane, Davao City',
                'supply_type' => 'Whole Chickens',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'supplier_name' => 'Rice & Grains Wholesale',
                'contact_person' => 'Sofia Martinez',
                'phone' => '+63 923 789 0123',
                'email' => 'sofia.martinez@ricegrains.com',
                'address' => '147 Grain Street, Davao City',
                'supply_type' => 'Rice and Grains',
                'status' => 'Active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert suppliers
        $db->table('suppliers')->insertBatch($suppliers);
    }
}

