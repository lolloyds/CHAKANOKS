<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveriesTable extends Migration
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
                'type'        => 'VARCHAR',
                'constraint'  => '50',
                'unique'      => true,
                'comment'     => 'DLV-001, DLV-002 format',
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Branch receiving the delivery',
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Supplier sending the delivery',
            ],
            'driver' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'Delivery driver name',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['scheduled', 'in_transit', 'delivered', 'received'],
                'default'    => 'scheduled',
                'comment'    => 'Delivery status',
            ],
            'scheduled_time' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'comment' => 'When delivery is expected',
            ],
            'arrival_time' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'When delivery arrived',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Additional delivery notes',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'User who created the delivery',
            ],
            'approved_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Branch user who approved the delivery',
            ],
            'approved_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
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
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('supplier_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('deliveries');
        // Add unique key after table creation to avoid conflicts
        $this->forge->addUniqueKey('deliveries', 'delivery_id');
    }

    public function down()
    {
        $this->forge->dropTable('deliveries');
    }
}
