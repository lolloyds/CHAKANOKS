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
}
