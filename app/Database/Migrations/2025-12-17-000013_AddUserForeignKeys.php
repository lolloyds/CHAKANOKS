<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserForeignKeys extends Migration
{
    public function up()
    {
        // Add foreign keys to users table after all other tables are created
        $this->forge->addForeignKey('users', 'branch_id', 'branches', 'id', 'SET NULL', 'CASCADE', 'users_branch_id_foreign');
        $this->forge->addForeignKey('users', 'supplier_id', 'suppliers', 'id', 'SET NULL', 'CASCADE', 'users_supplier_id_foreign');
    }

    public function down()
    {
        $this->forge->dropForeignKey('users', 'users_branch_id_foreign');
        $this->forge->dropForeignKey('users', 'users_supplier_id_foreign');
    }
}