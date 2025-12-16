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
            'purchase_order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Reference to purchase_orders.id',
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Branch receiving the delivery',
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Supplier sending the delivery',
            ],
            'driver_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Delivery driver name',
            ],
            'driver_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'comment'    => 'Driver contact number',
            ],
            'vehicle_info' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Vehicle plate number or description',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['scheduled', 'in_transit', 'delayed', 'arrived', 'delivered', 'received'],
                'default'    => 'scheduled',
                'comment'    => 'Delivery status workflow',
            ],
            'scheduled_time' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'When delivery is scheduled',
            ],
            'departure_time' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'When delivery started from supplier',
            ],
            'arrival_time' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'When delivery arrived at branch',
            ],
            'claimed_time' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'When delivery was claimed by branch',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Delivery notes and updates',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'User who created the delivery',
            ],
            'claimed_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Branch user who claimed the delivery',
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
        $this->forge->addForeignKey('purchase_order_id', 'purchase_orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('claimed_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('deliveries');
    }

    public function down()
    {
        $this->forge->dropTable('deliveries');
    }
}