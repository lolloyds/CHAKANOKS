<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStockMovementsTable extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true,
			],
			'item_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
			],
			'change_amount' => [
				'type' => 'INT',
				'constraint' => 11,
			],
			'reason' => [
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => true,
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => true,
			],
		]);

		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('item_id', 'items', 'id', 'CASCADE', 'CASCADE');
		$this->forge->createTable('stock_movements', true);
	}

	public function down()
	{
		$this->forge->dropTable('stock_movements', true);
	}
}


