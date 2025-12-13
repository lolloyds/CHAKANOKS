<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduledForDeliveryStatus extends Migration
{
    public function up()
    {
        // Alter the status column to include 'scheduled_for_delivery'
        $this->db->query("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'approved', 'po_issued_to_supplier', 'scheduled_for_delivery', 'ordered', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending'");
    }

    public function down()
    {
        // Revert back to previous ENUM values
        $this->db->query("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'approved', 'po_issued_to_supplier', 'ordered', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
}

