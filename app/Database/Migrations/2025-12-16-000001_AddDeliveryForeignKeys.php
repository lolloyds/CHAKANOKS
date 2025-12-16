<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeliveryForeignKeys extends Migration
{
    public function up()
    {
        // Add foreign keys to deliveries table
        if ($this->db->tableExists('deliveries') && $this->db->tableExists('branches')) {
            $this->forge->addForeignKey('deliveries', 'branch_id', 'branches', 'id', 'CASCADE', 'CASCADE', 'deliveries_branch_id_foreign');
        }
        
        if ($this->db->tableExists('deliveries') && $this->db->tableExists('suppliers')) {
            $this->forge->addForeignKey('deliveries', 'supplier_id', 'suppliers', 'id', 'SET NULL', 'CASCADE', 'deliveries_supplier_id_foreign');
        }
        
        if ($this->db->tableExists('deliveries') && $this->db->tableExists('purchase_orders')) {
            $this->forge->addForeignKey('deliveries', 'purchase_order_id', 'purchase_orders', 'id', 'SET NULL', 'CASCADE', 'deliveries_purchase_order_id_foreign');
        }
        
        if ($this->db->tableExists('deliveries') && $this->db->tableExists('users')) {
            $this->forge->addForeignKey('deliveries', 'created_by', 'users', 'id', 'CASCADE', 'CASCADE', 'deliveries_created_by_foreign');
            $this->forge->addForeignKey('deliveries', 'approved_by', 'users', 'id', 'SET NULL', 'CASCADE', 'deliveries_approved_by_foreign');
        }

        // Add foreign keys to delivery_items table
        if ($this->db->tableExists('delivery_items') && $this->db->tableExists('items')) {
            $this->forge->addForeignKey('delivery_items', 'item_id', 'items', 'id', 'CASCADE', 'CASCADE', 'delivery_items_item_id_foreign');
        }
        
        // Add foreign key for delivery_id (VARCHAR to VARCHAR reference)
        if ($this->db->tableExists('delivery_items') && $this->db->tableExists('deliveries')) {
            // Use raw SQL for VARCHAR to VARCHAR foreign key
            $this->db->query('ALTER TABLE delivery_items ADD CONSTRAINT delivery_items_delivery_id_foreign FOREIGN KEY (delivery_id) REFERENCES deliveries(delivery_id) ON DELETE CASCADE ON UPDATE CASCADE');
        }
    }

    public function down()
    {
        // Remove foreign keys
        if ($this->db->tableExists('deliveries')) {
            $this->forge->dropForeignKey('deliveries', 'deliveries_branch_id_foreign');
            $this->forge->dropForeignKey('deliveries', 'deliveries_supplier_id_foreign');
            $this->forge->dropForeignKey('deliveries', 'deliveries_purchase_order_id_foreign');
            $this->forge->dropForeignKey('deliveries', 'deliveries_created_by_foreign');
            $this->forge->dropForeignKey('deliveries', 'deliveries_approved_by_foreign');
        }
        
        if ($this->db->tableExists('delivery_items')) {
            $this->forge->dropForeignKey('delivery_items', 'delivery_items_item_id_foreign');
            $this->forge->dropForeignKey('delivery_items', 'delivery_items_delivery_id_foreign');
        }
    }
}