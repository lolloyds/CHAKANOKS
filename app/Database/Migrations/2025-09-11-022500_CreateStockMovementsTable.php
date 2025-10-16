<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'branch_id'      => ['type' => 'INT', 'unsigned' => true],
            'item_id'        => ['type' => 'INT', 'unsigned' => true],
            'movement_type'  => ['type' => 'ENUM', 'constraint' => [
                                    'receive',   // from supplier
                                    'use',       // consumption (sales, cooking)
                                    'transfer_in', // received from another branch
                                    'transfer_out', // sent to another branch
                                    'adjustment', // manual correction
                                    'spoilage'   // expired/damaged
                                ]],
            'quantity'       => ['type' => 'INT'],
            'remarks'        => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'created_by'     => ['type' => 'INT', 'unsigned' => true], // user who made the movement
            'created_at'     => ['type' => 'DATETIME', 'null' => false],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('stock_movements');
        // Add FKs after table creation to avoid order issues
        $this->forge->addForeignKey('stock_movements', 'branch_id', 'branches', 'id', 'CASCADE', 'CASCADE', 'stock_movements_branch_id_foreign');
        $this->forge->addForeignKey('stock_movements', 'item_id', 'items', 'id', 'CASCADE', 'CASCADE', 'stock_movements_item_id_foreign');
        $this->forge->addForeignKey('stock_movements', 'created_by', 'users', 'id', 'SET NULL', 'CASCADE', 'stock_movements_created_by_foreign');
    }

    public function down()
    {
        $this->forge->dropTable('stock_movements');
    }
}
