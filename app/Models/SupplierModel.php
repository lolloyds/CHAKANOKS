<?php

namespace App\Models;
use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $allowedFields = ['supplier_name','contact_person','phone','email','address','supply_type','status','deleted_at'];

    // Get stats including deleted
    public function getStats()
    {
        // Total including deleted
        $total = $this->withDeleted()->countAllResults(false);
        
        // Active (not deleted)
        $active = $this->where('status', 'Active')->where('deleted_at', null)->countAllResults(false);
        
        // Pending (not deleted)
        $pending = $this->where('status', 'Pending')->where('deleted_at', null)->countAllResults(false);
        
        // Inactive (not deleted)
        $inactive = $this->where('status', 'Inactive')->where('deleted_at', null)->countAllResults(false);
        
        // Deleted count
        $deleted = $this->onlyDeleted()->countAllResults(false);

        return [
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'inactive' => $inactive,
            'deleted' => $deleted
        ];
    }
    
    // Get all suppliers including deleted
    public function getAllWithDeleted()
    {
        return $this->withDeleted()->orderBy('id', 'DESC')->findAll();
    }
}
