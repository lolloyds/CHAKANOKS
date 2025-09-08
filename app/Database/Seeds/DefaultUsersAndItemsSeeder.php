<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DefaultUsersAndItemsSeeder extends Seeder
{
	public function run()
	{
		$now = Time::now()->toDateTimeString();

		// Users
		$users = [
			[
				'username' => 'admin',
				'password_hash' => password_hash('password', PASSWORD_DEFAULT),
				'role' => 'admin',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'username' => 'inventory',
				'password_hash' => password_hash('password', PASSWORD_DEFAULT),
				'role' => 'inventory_staff',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'username' => 'manager',
				'password_hash' => password_hash('password', PASSWORD_DEFAULT),
				'role' => 'branch_manager',
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'username' => 'user',
				'password_hash' => password_hash('password', PASSWORD_DEFAULT),
				'role' => 'user',
				'created_at' => $now,
				'updated_at' => $now,
			],
		];

		$this->db->table('users')->ignore(true)->insertBatch($users);

		// Items
		$items = [
			[
				'sku' => 'SKU-1001',
				'name' => 'Sample Widget A',
				'quantity' => 50,
				'min_quantity' => 10,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'sku' => 'SKU-1002',
				'name' => 'Sample Widget B',
				'quantity' => 5,
				'min_quantity' => 15,
				'created_at' => $now,
				'updated_at' => $now,
			],
			[
				'sku' => 'SKU-1003',
				'name' => 'Sample Widget C',
				'quantity' => 200,
				'min_quantity' => 25,
				'created_at' => $now,
				'updated_at' => $now,
			],
		];

		$this->db->table('items')->ignore(true)->insertBatch($items);
	}
}


