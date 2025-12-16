<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryModel extends Model
{
    protected $table            = 'deliveries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'delivery_id', 'purchase_order_id', 'branch_id', 'supplier_id', 
        'driver_name', 'driver_phone', 'vehicle_info', 'status',
        'scheduled_time', 'departure_time', 'arrival_time', 'claimed_time',
        'notes', 'created_by', 'claimed_by', 'created_at', 'updated_at'
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
     * Generate next delivery ID
     */
    public function generateDeliveryId()
    {
        $lastDelivery = $this->orderBy('id', 'DESC')->first();
        if ($lastDelivery && isset($lastDelivery['delivery_id'])) {
            $lastNumber = (int) substr($lastDelivery['delivery_id'], 4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        return 'DLV-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
