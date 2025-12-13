<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPendingCentralOfficeReviewStatus extends Migration
{
    public function up()
    {
        // Alter the status column to include 'pending central office review'
        $this->db->query("ALTER TABLE purchase_requests MODIFY COLUMN status ENUM('pending', 'pending central office review', 'approved', 'rejected', 'converted') DEFAULT 'pending'");
    }

    public function down()
    {
        // Revert back to original ENUM values
        $this->db->query("ALTER TABLE purchase_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'converted') DEFAULT 'pending'");
    }
}

