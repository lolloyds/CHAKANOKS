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
                'comment'        => 'Item being delivered',
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'comment'    => 'Quantity of this item in delivery',
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
        // FK constraints - add after both tables exist (in a separate migration if needed)
        // $this->forge->addForeignKey('delivery_id', 'deliveries', 'delivery_id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('item_id', 'items', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('delivery_items');
    }
}
