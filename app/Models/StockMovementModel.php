<?php

namespace App\Models;

use CodeIgniter\Model;

class StockMovementModel extends Model
{
    protected $table = 'stock_movements';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'branch_id', 'item_id', 'movement_type', 'quantity',
        'remarks', 'created_by', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
}
