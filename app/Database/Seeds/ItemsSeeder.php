<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ItemsSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now()->toDateTimeString();

        $items = [
            // Main Ingredient
            [
                'name'          => 'Whole Chicken',
                'category'      => 'Meat',
                'unit'          => 'pcs',
                'barcode'       => '100001',
                'perishable'    => true,
                'reorder_level' => 20,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],

            // Marinades & Sauces
            [
                'name'          => 'Soy Sauce',
                'category'      => 'Condiments',
                'unit'          => 'liters',
                'barcode'       => '200001',
                'perishable'    => false,
                'reorder_level' => 10,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Calamansi Juice',
                'category'      => 'Condiments',
                'unit'          => 'liters',
                'barcode'       => '200002',
                'perishable'    => true,
                'reorder_level' => 5,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],

            // Fresh Aromatics
            [
                'name'          => 'Garlic',
                'category'      => 'Vegetables',
                'unit'          => 'kg',
                'barcode'       => '300001',
                'perishable'    => true,
                'reorder_level' => 5,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Onion',
                'category'      => 'Vegetables',
                'unit'          => 'kg',
                'barcode'       => '300002',
                'perishable'    => true,
                'reorder_level' => 8,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Lemongrass',
                'category'      => 'Herbs',
                'unit'          => 'bundles',
                'barcode'       => '300003',
                'perishable'    => true,
                'reorder_level' => 10,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],

            // Seasonings & Spices
            [
                'name'          => 'Ground Black Pepper',
                'category'      => 'Seasonings',
                'unit'          => 'kg',
                'barcode'       => '400001',
                'perishable'    => false,
                'reorder_level' => 2,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Brown Sugar',
                'category'      => 'Seasonings',
                'unit'          => 'kg',
                'barcode'       => '400002',
                'perishable'    => false,
                'reorder_level' => 10,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Salt',
                'category'      => 'Seasonings',
                'unit'          => 'kg',
                'barcode'       => '400003',
                'perishable'    => false,
                'reorder_level' => 5,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],

            // Cooking Oil
            [
                'name'          => 'Cooking Oil',
                'category'      => 'Cooking Supplies',
                'unit'          => 'liters',
                'barcode'       => '500001',
                'perishable'    => false,
                'reorder_level' => 20,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        $this->db->table('items')->insertBatch($items);
    }
}