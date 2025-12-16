<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table            = 'purchase_orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'po_id', 'purchase_request_id', 'supplier_id', 'branch_id', 'order_date',
        'expected_delivery_date', 'status', 'total_cost', 'notes',
        'created_by', 'approved_by', 'approved_at', 'created_at', 'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

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
     * Generate next PO ID
     */
    public function generatePoId()
    {
        $lastPO = $this->orderBy('id', 'DESC')->first();
        if ($lastPO && isset($lastPO['po_id'])) {
            $lastNumber = (int) substr($lastPO['po_id'], 3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        return 'PO-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get purchase orders with supplier and branch info
     */
    public function getOrdersWithDetails($branchId = null, $supplierId = null)
    {
        $db = \Config\Database::connect();
        $query = $db->table('purchase_orders po')
            ->select('po.*, s.supplier_name, b.name as branch_name,
                     u1.username as created_by_name,
                     u2.username as approved_by_name,
                     pr.pr_id as pr_request_id')
            ->join('suppliers s', 'po.supplier_id = s.id', 'left')
            ->join('branches b', 'po.branch_id = b.id', 'left')
            ->join('users u1', 'po.created_by = u1.id', 'left')
            ->join('users u2', 'po.approved_by = u2.id', 'left')
            ->join('purchase_requests pr', 'po.purchase_request_id = pr.id', 'left')
            ->orderBy('po.created_at', 'DESC');

        if ($branchId) {
            $query->where('po.branch_id', $branchId);
        }
        
        if ($supplierId) {
            $query->where('po.supplier_id', $supplierId);
        }

        return $query->get()->getResultArray();
    }

    /**
     * Get order with items
     */
    public function getOrderWithItems($orderId)
    {
        $order = $this->find($orderId);
        if (!$order) {
            return null;
        }

        $db = \Config\Database::connect();
        $items = $db->table('purchase_order_items poi')
            ->select('poi.*, i.name as item_name_from_db, i.unit as item_unit_from_db')
            ->join('items i', 'poi.item_id = i.id', 'left')
            ->where('poi.purchase_order_id', $orderId)
            ->get()
            ->getResultArray();

        $order['items'] = $items;
        return $order;
    }

    /**
     * Get stats
     */
    public function getStats($branchId = null, $supplierId = null)
    {
        $query = $this;
        if ($branchId) {
            $query = $query->where('branch_id', $branchId);
        }
        
        if ($supplierId) {
            $query = $query->where('supplier_id', $supplierId);
        }

        return [
            'total' => $query->countAllResults(false),
            'pending' => $query->where('status', 'pending')->countAllResults(false),
            'pending_delivery_schedule' => $query->where('status', 'pending_delivery_schedule')->countAllResults(false),
            'scheduled_for_delivery' => $query->where('status', 'scheduled_for_delivery')->countAllResults(false),
            'ordered' => $query->where('status', 'ordered')->countAllResults(false),
            'in_transit' => $query->where('status', 'in_transit')->countAllResults(false),
            'delayed' => $query->where('status', 'delayed')->countAllResults(false),
            'arrived' => $query->where('status', 'arrived')->countAllResults(false),
            'delivered' => $query->where('status', 'delivered')->countAllResults(false),
            'delivered_to_branch' => $query->where('status', 'delivered_to_branch')->countAllResults(false),
            'completed' => $query->where('status', 'completed')->countAllResults(false),
        ];
    }
}
