<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItemsTable extends Migration
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
			'sku' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
				'unique' => true,
			],
			'name' => [
				'type' => 'VARCHAR',
				'constraint' => '255',
			],
			'quantity' => [
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			],
			'type => [
				'type' => '',
				'constraint' => 11,
				'default' => 0,
			],
			'min_quantity' => [
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			],
			'created_at' => [
				'type' => 'DATETIME',
				'null' => true,
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => true,
			],
		]);

		$this->forge->addKey('id', true);
		$this->forge->createTable('items', true);
	}

	public function down()
	{
		$this->forge->dropTable('items', true);
	}
}


