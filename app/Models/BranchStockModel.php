<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchStockModel extends Model
{
    protected $table            = 'branch_stock';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'branch_id', 'item_id', 'quantity', 'expiry_date', 'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get stock for a specific branch
     */
    public function getBranchStock($branchId)
    {
        return $this->select('branch_stock.*, items.name as item_name, items.unit, items.reorder_level, items.perishable')
                    ->join('items', 'branch_stock.item_id = items.id')
                    ->where('branch_stock.branch_id', $branchId)
                    ->where('items.status', 'active')
                    ->findAll();
    }

    /**
     * Get low stock items for a branch
     */
    public function getLowStock($branchId = null)
    {
        $builder = $this->select('branch_stock.*, items.name as item_name, items.unit, items.reorder_level')
                        ->join('items', 'branch_stock.item_id = items.id')
                        ->where('items.status', 'active')
                        ->where('branch_stock.quantity <=', 'items.reorder_level', false);
        
        if ($branchId) {
            $builder->where('branch_stock.branch_id', $branchId);
        }
        
        return $builder->findAll();
    }

    /**
     * Get items near expiry
     */
    public function getNearExpiry($branchId = null, $days = 7)
    {
        $expiryDate = date('Y-m-d', strtotime("+{$days} days"));
        
        $builder = $this->select('branch_stock.*, items.name as item_name, items.unit')
                        ->join('items', 'branch_stock.item_id = items.id')
                        ->where('items.status', 'active')
                        ->where('items.perishable', true)
                        ->where('branch_stock.expiry_date IS NOT NULL')
                        ->where('branch_stock.expiry_date <=', $expiryDate)
                        ->where('branch_stock.quantity >', 0);
        
        if ($branchId) {
            $builder->where('branch_stock.branch_id', $branchId);
        }
        
        return $builder->findAll();
    }
}