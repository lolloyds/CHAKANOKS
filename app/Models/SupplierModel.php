<?php

namespace App\Models;
use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['supplier_name','contact_person','phone','email','supply_type','status'];

    // Get stats
    public function getStats()
    {
        $total = $this->countAllResults(false);
        $active = $this->where('status', 'Active')->countAllResults(false);
        $pending = $this->where('status', 'Pending')->countAllResults(false);
        $inactive = $this->where('status', 'Inactive')->countAllResults(false);

        return [
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'inactive' => $inactive
        ];
    }
}
