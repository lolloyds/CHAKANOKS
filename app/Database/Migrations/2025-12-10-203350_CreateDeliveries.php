<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveries extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'supplier_id' => [
                'type' => 'INT',
                'unsigned' => true
            ],
            'item_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'quantity' => [
                'type' => 'INT'
            ],
            'delivery_date' => [
                'type' => 'DATE'
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'Scheduled'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP'
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('deliveries');
    }

    public function down()
    {
        $this->forge->dropTable('deliveries');
    }
}
