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
                'constraint' => '10,2',
                'null'       => false,
                'comment'    => 'Quantity of this item in delivery',
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'comment'    => 'Unit of measurement',
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'comment'    => 'Cost per unit of this item',
            ],
            'expiry_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'Expiry date of delivered items',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['in_transit', 'delivered', 'received', 'damaged', 'expired'],
                'default'    => 'in_transit',
                'comment'    => 'Individual item status within delivery',
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
        $this->forge->createTable('delivery_items');
        // Note: delivery_id FK will be added after deliveries table is created
    }

    public function down()
    {
        $this->forge->dropTable('delivery_items');
    }
}
