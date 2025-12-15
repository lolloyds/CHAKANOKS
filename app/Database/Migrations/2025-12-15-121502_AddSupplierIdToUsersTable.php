<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierIdToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'supplier_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'branch_id',
            ],
        ]);

        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('users', 'users_supplier_id_foreign');
        $this->forge->dropColumn('users', 'supplier_id');
    }
}
