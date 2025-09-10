<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyToUsersTable extends Migration
{
    public function up()
    {
        // Add foreign key constraint to users table after branches table is created
        $this->db->query('ALTER TABLE users ADD CONSTRAINT users_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Remove foreign key constraint
        $this->db->query('ALTER TABLE users DROP FOREIGN KEY users_branch_id_foreign');
    }
}
