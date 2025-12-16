<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePurchaseRequestsTable extends Migration
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
            'pr_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
                'comment'    => 'PR-001, PR-002 format',
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'Branch making the request',
            ],
            'request_date' => [
                'type'    => 'DATE',
                'null'    => false,
            ],
            'needed_by_date' => [
                'type'    => 'DATE',
                'null'    => true,
                'comment' => 'When items are needed',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending_central_office_review', 'approved', 'rejected', 'converted'],
                'default'    => 'pending_central_office_review',
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'urgent'],
                'default'    => 'medium',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'comment'    => 'User who created the PR',
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
            'rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Reason for rejection if status is rejected',
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
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('purchase_requests');
    }

    public function down()
    {
        $this->forge->dropTable('purchase_requests');
    }
}