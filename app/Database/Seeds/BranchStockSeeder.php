<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class BranchStockSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now()->toDateTimeString();

        // Initial stock for Branch 1 (some items to show in inventory)
        $branchStock = [
            // Branch 1 stock
            ['branch_id' => 1, 'item_id' => 1, 'quantity' => 50, 'expiry_date' => date('Y-m-d', strtotime('+30 days')), 'updated_at' => $now], // Whole Chicken (perishable)
            ['branch_id' => 1, 'item_id' => 4, 'quantity' => 20, 'expiry_date' => date('Y-m-d', strtotime('+7 days')), 'updated_at' => $now], // Garlic (perishable)
            ['branch_id' => 1, 'item_id' => 2, 'quantity' => 15, 'expiry_date' => null, 'updated_at' => $now], // Soy Sauce (non-perishable)
            ['branch_id' => 1, 'item_id' => 10, 'quantity' => 25, 'expiry_date' => null, 'updated_at' => $now], // Cooking Oil (non-perishable)
            ['branch_id' => 1, 'item_id' => 9, 'quantity' => 8, 'expiry_date' => null, 'updated_at' => $now], // Salt (low stock)
            
            // Branch 2 stock (smaller amounts)
            ['branch_id' => 2, 'item_id' => 1, 'quantity' => 25, 'expiry_date' => date('Y-m-d', strtotime('+25 days')), 'updated_at' => $now], // Whole Chicken
            ['branch_id' => 2, 'item_id' => 2, 'quantity' => 10, 'expiry_date' => null, 'updated_at' => $now], // Soy Sauce
            ['branch_id' => 2, 'item_id' => 10, 'quantity' => 15, 'expiry_date' => null, 'updated_at' => $now], // Cooking Oil
        ];

        $this->db->table('branch_stock')->insertBatch($branchStock);
    }
}