<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class BranchStockSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now()->toDateTimeString();

        $stock = [
            // Branch 1
            ['branch_id' => 1, 'item_id' => 1, 'quantity' => 50, 'updated_at' => $now], // Chicken
            ['branch_id' => 1, 'item_id' => 2, 'quantity' => 20, 'updated_at' => $now], // Lettuce
            ['branch_id' => 1, 'item_id' => 3, 'quantity' => 100, 'updated_at' => $now], // Rice

            // Branch 2
            ['branch_id' => 2, 'item_id' => 1, 'quantity' => 30, 'updated_at' => $now], 
            ['branch_id' => 2, 'item_id' => 2, 'quantity' => 15, 'updated_at' => $now],
            ['branch_id' => 2, 'item_id' => 4, 'quantity' => 25, 'updated_at' => $now], // Oil
        ];

        $this->db->table('branch_stock')->insertBatch($stock);
    }
}
