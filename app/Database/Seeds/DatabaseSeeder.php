<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $this->call('BranchesSeeder');

        $this->call('ItemsSeeder');

        $this->call('UsersSeeder');

        $this->call('BranchStockSeeder');

        $this->call('DeliveriesSeeder');
    }
}
