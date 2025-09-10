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
            [
                'name'          => 'Chicken Breast',
                'unit'          => 'kg',
                'barcode'       => '100001',
                'perishable'    => true,
                'reorder_level' => 10,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Lettuce',
                'unit'          => 'kg',
                'barcode'       => '100002',
                'perishable'    => true,
                'reorder_level' => 5,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Rice',
                'unit'          => 'kg',
                'barcode'       => '100003',
                'perishable'    => false,
                'reorder_level' => 20,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Cooking Oil',
                'unit'          => 'liter',
                'barcode'       => '100004',
                'perishable'    => false,
                'reorder_level' => 10,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Softdrinks',
                'unit'          => 'bottle',
                'barcode'       => '100005',
                'perishable'    => false,
                'reorder_level' => 15,
                'status'        => 'active',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        $this->db->table('items')->insertBatch($items);
    }
}
