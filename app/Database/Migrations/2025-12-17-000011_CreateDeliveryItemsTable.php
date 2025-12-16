<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveryItemsTable extends Migration
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
            'delivery_id' => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => false,
                'comment'        => 'Reference to deliveries.delivery_id',
            ],
            'item_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                'comment'        => 'Item being delivered',
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
                'comment'    => 'Name of the item being delivered',
            ],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => false,
                'comment'    => 'Quantity of this item in delivery',
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
                'comment'    => 'Unit of measurement',
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'null'       => true,
                'default'    => 0.00,
                'comment'    => 'Cost per unit of this item',
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
                'default'    => 0.00,
                'comment'    => 'Total cost for this item',
            ],
            'expiry_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'Expiry date of delivered items',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['scheduled', 'in_transit', 'delayed', 'arrived', 'delivered', 'received'],
                'default'    => 'scheduled',
                'comment'    => 'Individual item status within delivery',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Item-specific delivery notes',
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
        $this->forge->addForeignKey('item_id', 'items', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('delivery_items');
    }

    public function down()
    {
        $this->forge->dropTable('delivery_items');
    }
}