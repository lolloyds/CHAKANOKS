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
			

		];

		$this->db->table('users')->ignore(true)->insertBatch($users);

	
	}
}


