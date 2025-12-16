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
                'null'       => true,
                'comment'    => 'Branch this alert is for (null for system-wide)',
            ],
            'item_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Item this alert is about (null for general alerts)',
            ],
            'alert_type' => [
                'type'       => 'ENUM',
                'constraint' => ['low_stock', 'expiry_warning', 'system', 'delivery', 'order'],
                'null'       => false,
            ],
            'severity' => [
                'type'       => 'ENUM',
                'constraint' => ['info', 'warning', 'critical'],
                'default'    => 'info',
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'is_read' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'read_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'User who marked as read',
            ],
            'read_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'expires_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'comment' => 'When alert should be auto-dismissed',
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
        $this->forge->addForeignKey('item_id', 'items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('read_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('alerts');
    }

    public function down()
    {
        $this->forge->dropTable('alerts');
    }
}