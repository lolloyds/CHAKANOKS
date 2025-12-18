<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFranchiseIdToBranchesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('branches', [
            'franchise_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        // Add foreign key constraint
        $this->forge->addForeignKey('franchise_id', 'franchises', 'id', 'SET NULL', 'CASCADE', 'branches');
    }

    public function down()
    {
        $this->forge->dropForeignKey('branches', 'branches_franchise_id_foreign');
        $this->forge->dropColumn('branches', 'franchise_id');
    }
}