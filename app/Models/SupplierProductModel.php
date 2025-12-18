<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierProductModel extends Model
{
    protected $table = 'supplier_products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'supplier_id', 'item_id', 'price_per_unit', 'minimum_order', 
        'availability_status', 'lead_time_days', 'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get supplier products with item details
     */
    public function getSupplierProductsWithDetails($supplierId)
    {
        $db = \Config\Database::connect();
        return $db->table('supplier_products sp')
            ->select('sp.*, i.name as item_name, i.unit, i.category')
            ->join('items i', 'sp.item_id = i.id', 'left')
            ->where('sp.supplier_id', $supplierId)
            ->orderBy('i.category', 'ASC')
            ->orderBy('i.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get available items not yet added by supplier
     */
    public function getAvailableItemsForSupplier($supplierId)
    {
        $db = \Config\Database::connect();
        return $db->table('items i')
            ->select('i.*')
            ->where('i.id NOT IN (SELECT item_id FROM supplier_products WHERE supplier_id = ' . $supplierId . ')')
            ->orderBy('i.category', 'ASC')
            ->orderBy('i.item_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get supplier for a specific item by item name
     */
    public function getSupplierForItem($itemName)
    {
        $db = \Config\Database::connect();
        return $db->table('supplier_products sp')
            ->select('sp.supplier_id, s.supplier_name, sp.price_per_unit')
            ->join('items i', 'sp.item_id = i.id', 'inner')
            ->join('suppliers s', 'sp.supplier_id = s.id', 'inner')
            ->where('i.name', $itemName)
            ->where('sp.availability_status', 'available')
            ->where('s.status', 'Active')
            ->get()
            ->getRowArray();
    }

    /**
     * Get suppliers for multiple items
     */
    public function getSuppliersForItems($itemNames)
    {
        $db = \Config\Database::connect();
        return $db->table('supplier_products sp')
            ->select('sp.supplier_id, s.supplier_name, i.name as item_name, sp.price_per_unit')
            ->join('items i', 'sp.item_id = i.id', 'inner')
            ->join('suppliers s', 'sp.supplier_id = s.id', 'inner')
            ->whereIn('i.name', $itemNames)
            ->where('sp.availability_status', 'available')
            ->where('s.status', 'Active')
            ->get()
            ->getResultArray();
    }
}