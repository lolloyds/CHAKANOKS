<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
	protected $table = 'items';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $allowedFields = ['sku', 'name', 'quantity', 'min_quantity', 'created_at', 'updated_at'];
	protected $useTimestamps = true;
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}


