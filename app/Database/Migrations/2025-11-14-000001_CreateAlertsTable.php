<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAlertsTable extends Migration
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
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // null for system-wide alerts
            ],
            'item_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'alert_type' => [
                'type'       => 'ENUM',
                'constraint' => ['low_stock', 'out_of_stock', 'near_expiry', 'expired', 'system'],
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'severity' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default'    => 'medium',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'acknowledged', 'resolved'],
                'default'    => 'active',
            ],
            'acknowledged_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'User who acknowledged the alert',
            ],
            'acknowledged_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'resolved_at' => [
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
        $this->forge->addForeignKey('branch_id', 'branches', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('item_id', 'items', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('acknowledged_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('alerts');

        // Add indexes for performance
        $this->forge->addKey(['branch_id', 'status']);
        $this->forge->addKey(['alert_type', 'status']);
        $this->forge->addKey('created_at');
    }

    public function down()
    {
        $this->forge->dropTable('alerts');
    }
}
