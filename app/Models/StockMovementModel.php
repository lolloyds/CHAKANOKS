<?php

namespace App\Models;

use CodeIgniter\Model;

class StockMovementModel extends Model
{
	protected $table = 'stock_movements';
	protected $primaryKey = 'id';
	protected $returnType = 'array';
	protected $allowedFields = ['item_id', 'change_amount', 'reason', 'created_at'];
	public $useTimestamps = false;
}


