<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupplierProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'item_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'price_per_unit' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
                'comment'    => 'Price per unit/piece',
            ],
            'minimum_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'comment'    => 'Minimum order quantity',
            ],
            'availability_status' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'out_of_stock', 'discontinued'],
                'default'    => 'available',
            ],
            'lead_time_days' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'comment'    => 'Lead time in days',
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Additional notes about the product',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['supplier_id', 'item_id']); // Prevent duplicate supplier-item combinations
        $this->forge->createTable('supplier_products');
    }

    public function down()
    {
        $this->forge->dropTable('supplier_products');
    }
}