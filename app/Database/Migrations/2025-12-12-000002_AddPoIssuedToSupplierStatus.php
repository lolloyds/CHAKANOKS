<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPoIssuedToSupplierStatus extends Migration
{
    public function up()
    {
        // Alter the status column to include 'po_issued_to_supplier'
        $this->db->query("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'approved', 'po_issued_to_supplier', 'ordered', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending'");
    }

    public function down()
    {
        // Revert back to original ENUM values
        $this->db->query("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'approved', 'ordered', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
}

