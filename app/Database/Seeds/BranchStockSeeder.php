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
            ['branch_id' => 1, 'item_id' => 1, 'quantity' => 50, 'expiry_date' => date('Y-m-d', strtotime('+30 days')), 'updated_at' => $now], // Chicken (perishable)
            ['branch_id' => 1, 'item_id' => 2, 'quantity' => 20, 'expiry_date' => date('Y-m-d', strtotime('+7 days')), 'updated_at' => $now], // Lettuce (perishable)
            ['branch_id' => 1, 'item_id' => 3, 'quantity' => 100, 'expiry_date' => null, 'updated_at' => $now], // Rice (non-perishable)
            ['branch_id' => 1, 'item_id' => 5, 'quantity' => 60, 'expiry_date' => null, 'updated_at' => $now], // Softdrinks (non-perishable)

            // Branch 2
            ['branch_id' => 2, 'item_id' => 1, 'quantity' => 30, 'expiry_date' => date('Y-m-d', strtotime('+25 days')), 'updated_at' => $now], // Chicken (perishable)
            ['branch_id' => 2, 'item_id' => 2, 'quantity' => 15, 'expiry_date' => date('Y-m-d', strtotime('+5 days')), 'updated_at' => $now], // Lettuce (perishable)
            ['branch_id' => 2, 'item_id' => 4, 'quantity' => 25, 'expiry_date' => null, 'updated_at' => $now], // Oil (non-perishable)
        ];

        $this->db->table('branch_stock')->insertBatch($stock);
    }
}
