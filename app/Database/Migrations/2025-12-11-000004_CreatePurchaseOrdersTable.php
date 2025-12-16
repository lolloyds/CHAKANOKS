<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseOrdersTable extends Migration
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
            'po_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
                'comment'    => 'PO-001, PO-002 format',
            ],
            'purchase_request_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Reference to purchase_requests if created from PR',
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Supplier for this PO',
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Branch receiving items (if branch-specific)',
            ],
            'order_date' => [
                'type'    => 'DATE',
                'null'    => false,
            ],
            'expected_delivery_date' => [
                'type'    => 'DATE',
                'null'    => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'pending_delivery_schedule', 'scheduled_for_delivery', 'ordered', 'in_transit', 'delayed', 'arriving', 'delivered', 'delivered_to_branch', 'completed', 'cancelled'],
                'default'    => 'pending',
            ],
            'total_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'User who created the PO',
            ],
            'approved_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        $this->forge->addForeignKey('purchase_request_id', 'purchase_requests', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('supplier_id', 'suppliers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('purchase_orders');
    }

    public function down()
    {
        $this->forge->dropTable('purchase_orders');
    }
}
