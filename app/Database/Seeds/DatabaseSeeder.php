<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Essential seeders only
        $this->call('FranchisesSeeder');
        $this->call('BranchesSeeder');
        $this->call('SuppliersSeeder');
        $this->call('ItemsSeeder');
        $this->call('UsersSeeder');
        $this->call('BranchStockSeeder');
        $this->call('SupplierProductsSeeder');
    }
}
