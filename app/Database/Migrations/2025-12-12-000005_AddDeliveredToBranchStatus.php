<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeliveredToBranchStatus extends Migration
{
    public function up()
    {
        // Alter the status column to include 'delivered_to_branch'
        $this->db->query("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'approved', 'po_issued_to_supplier', 'scheduled_for_delivery', 'ordered', 'in_transit', 'delayed', 'arriving', 'delivered', 'delivered_to_branch', 'cancelled') DEFAULT 'pending'");
    }

    public function down()
    {
        // Revert back to previous ENUM values
        $this->db->query("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'approved', 'po_issued_to_supplier', 'scheduled_for_delivery', 'ordered', 'in_transit', 'delayed', 'arriving', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
}

