<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyToUsersTable extends Migration
{
    public function up()
    {
        // Foreign key is now added directly in CreateUsersTable migration
        // This migration is kept empty to avoid conflicts
    }

    public function down()
    {
        // Foreign key is now added directly in CreateUsersTable migration
        // This migration is kept empty to avoid conflicts
    }
}
