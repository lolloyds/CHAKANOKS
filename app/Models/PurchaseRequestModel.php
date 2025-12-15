<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseRequestModel extends Model
{
    protected $table            = 'purchase_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'request_id', 'branch_id', 'date_needed', 'status', 'notes',
        'requested_by', 'approved_by', 'approved_at', 'rejection_reason',
        'created_at', 'updated_at'
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
     * Generate next request ID
     */
    public function generateRequestId()
    {
        $lastRequest = $this->orderBy('id', 'DESC')->first();
        if ($lastRequest && isset($lastRequest['request_id'])) {
            $lastNumber = (int) substr($lastRequest['request_id'], 3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        return 'PR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get purchase requests with branch and user info
     */
    public function getRequestsWithDetails($branchId = null)
    {
        $db = \Config\Database::connect();
        $query = $db->table('purchase_requests pr')
            ->select('pr.*, b.name as branch_name, 
                     u1.username as requested_by_name,
                     u2.username as approved_by_name')
            ->join('branches b', 'pr.branch_id = b.id', 'left')
            ->join('users u1', 'pr.requested_by = u1.id', 'left')
            ->join('users u2', 'pr.approved_by = u2.id', 'left')
            ->orderBy('pr.created_at', 'DESC');

        if ($branchId) {
            $query->where('pr.branch_id', $branchId);
        }

        return $query->get()->getResultArray();
    }

    /**
     * Get request with items
     */
    public function getRequestWithItems($requestId)
    {
        $db = \Config\Database::connect();

        // Get request with branch and requester/approver names
        $request = $db->table('purchase_requests pr')
            ->select('pr.*, b.name as branch_name, u1.username as requested_by_name, u2.username as approved_by_name')
            ->join('branches b', 'pr.branch_id = b.id', 'left')
            ->join('users u1', 'pr.requested_by = u1.id', 'left')
            ->join('users u2', 'pr.approved_by = u2.id', 'left')
            ->where('pr.id', $requestId)
            ->get()
            ->getRowArray();

        if (!$request) {
            return null;
        }

        // Load items for this request and normalize field names
        $items = $db->table('purchase_request_items pri')
            ->select('pri.*, i.name as item_name_from_db, i.unit as item_unit_from_db')
            ->join('items i', 'pri.item_id = i.id', 'left')
            ->where('pri.purchase_request_id', $requestId)
            ->get()
            ->getResultArray();

        // Normalize item fields so view code can use 'item_name' and 'unit'
        foreach ($items as &$it) {
            if (empty($it['item_name']) && !empty($it['item_name_from_db'])) {
                $it['item_name'] = $it['item_name_from_db'];
            }
            if (empty($it['unit']) && !empty($it['item_unit_from_db'])) {
                $it['unit'] = $it['item_unit_from_db'];
            }
        }

        $request['items'] = $items;
        return $request;
    }

    /**
     * Get stats
     */
    public function getStats($branchId = null)
    {
        $query = $this;
        if ($branchId) {
            $query = $query->where('branch_id', $branchId);
        }

        return [
            'total' => $query->countAllResults(false),
            'pending' => $query->where('status', 'pending')->countAllResults(false),
            'pending_central_office_review' => $query->where('status', 'pending central office review')->countAllResults(false),
            'approved' => $query->where('status', 'approved')->countAllResults(false),
            'rejected' => $query->where('status', 'rejected')->countAllResults(false),
            'converted' => $query->where('status', 'converted')->countAllResults(false),
        ];
    }
}




