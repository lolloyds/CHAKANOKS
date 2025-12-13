<?php

namespace App\Models;

use CodeIgniter\Model;

class FranchiseModel extends Model
{
    protected $table            = 'franchises';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'franchise_id', 'branch_name', 'owner_name', 'location', 'contact_number', 'email',
        'status', 'monthly_sales', 'monthly_royalty', 'application_date', 'approval_date', 'notes',
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
     * Generate next franchise ID
     */
    public function generateFranchiseId()
    {
        $lastFranchise = $this->orderBy('id', 'DESC')->first();
        if ($lastFranchise && isset($lastFranchise['franchise_id'])) {
            $lastNumber = (int) substr($lastFranchise['franchise_id'], 3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        return 'FR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get franchise statistics
     */
    public function getStats()
    {
        return [
            'total' => $this->countAllResults(false),
            'active' => $this->where('status', 'Active')->countAllResults(false),
            'pending_approval' => $this->where('status', 'Pending Approval')->countAllResults(false),
            'in_progress' => $this->where('status', 'Application In Progress')->countAllResults(false),
            'total_monthly_royalty' => $this->selectSum('monthly_royalty')->first()['monthly_royalty'] ?? 0,
        ];
    }
}

