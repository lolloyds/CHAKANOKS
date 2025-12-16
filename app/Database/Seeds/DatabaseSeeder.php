<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $this->call('BranchesSeeder');

        $this->call('SuppliersSeeder');

        $this->call('UsersSeeder');

        $this->call('FranchisesSeeder');

    }
}
