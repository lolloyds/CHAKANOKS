<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SupplierProductsSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now()->toDateTimeString();

        $supplierProducts = [
            [
                'supplier_id' => 1,
                'item_id' => 1, // Whole Chicken
                'price_per_unit' => 180.00,
                'minimum_order' => 5,
                'availability_status' => 'available',
                'lead_time_days' => 2,
                'notes' => 'Fresh whole chicken, 1.2-1.5kg average weight',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'supplier_id' => 2,
                'item_id' => 2,
                'price_per_unit' => 85.00,
                'minimum_order' => 6,
                'availability_status' => 'available',
                'lead_time_days' => 1,
                'notes' => 'Premium soy sauce, 1 liter bottle',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'item_id' => 3, // Calamansi Juice
                'price_per_unit' => 120.00,
                'minimum_order' => 4,
                'availability_status' => 'available',
                'lead_time_days' => 1,
                'notes' => 'Fresh calamansi juice, 1 liter bottle',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'item_id' => 4, // Garlic
                'price_per_unit' => 150.00,
                'minimum_order' => 3,
                'availability_status' => 'available',
                'lead_time_days' => 1,
                'notes' => 'Fresh garlic, 1kg pack',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'item_id' => 5, // Onion
                'price_per_unit' => 80.00,
                'minimum_order' => 5,
                'availability_status' => 'available',
                'lead_time_days' => 1,
                'notes' => 'Fresh red onions, 1kg pack',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'item_id' => 6, // Lemongrass
                'price_per_unit' => 25.00,
                'minimum_order' => 10,
                'availability_status' => 'available',
                'lead_time_days' => 1,
                'notes' => 'Fresh lemongrass bundles',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'item_id' => 7, // Ground Black Pepper
                'price_per_unit' => 450.00,
                'minimum_order' => 2,
                'availability_status' => 'available',
                'lead_time_days' => 2,
                'notes' => 'Premium ground black pepper, 1kg pack',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'item_id' => 8, // Brown Sugar
                'price_per_unit' => 65.00,
                'minimum_order' => 10,
                'availability_status' => 'available',
                'lead_time_days' => 1,
                'notes' => 'Brown sugar, 1kg pack',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'item_id' => 9, // Salt
                'price_per_unit' => 20.00,
                'minimum_order' => 20,
                'availability_status' => 'available',
                'lead_time_days' => 1,
                'notes' => 'Iodized salt, 1kg pack',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'supplier_id' => 2,
                'item_id' => 10, // Cooking Oil
                'price_per_unit' => 95.00,
                'minimum_order' => 8,
                'availability_status' => 'available',
                'lead_time_days' => 1,
                'notes' => 'Vegetable cooking oil, 1 liter bottle',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('supplier_products')->insertBatch($supplierProducts);
    }
}