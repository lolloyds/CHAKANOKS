<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $allowedFields = ['username', 'email', 'password', 'role', 'branch_id', 'supplier_id', 'status', 'last_login', 'created_at', 'updated_at'];
	protected $useTimestamps = true;
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';

	/**
	 * Get users with branch and supplier details
	 */
	public function getUsersWithDetails()
	{
		$db = \Config\Database::connect();
		return $db->table('users u')
			->select('u.*, b.name as branch_name, s.supplier_name')
			->join('branches b', 'u.branch_id = b.id', 'left')
			->join('suppliers s', 'u.supplier_id = s.id', 'left')
			->orderBy('u.created_at', 'DESC')
			->get()
			->getResultArray();
	}
}
