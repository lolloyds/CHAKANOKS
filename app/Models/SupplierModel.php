<?php

namespace App\Models;
use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['supplier_name','contact_person','phone','email','address','status','created_at','updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get stats
    public function getStats()
    {
        // Total suppliers
        $total = $this->countAllResults(false);
        
        // Active suppliers
        $active = $this->where('status', 'Active')->countAllResults(false);
        
        // Inactive suppliers
        $inactive = $this->where('status', 'Inactive')->countAllResults(false);

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive
        ];
    }
}
