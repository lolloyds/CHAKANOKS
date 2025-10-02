<?php

namespace App\Services;

use CodeIgniter\Database\ConnectionInterface;

class InventoryService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ✅ Receive stock (delivery from supplier)
    public function receiveStock($branchId, $itemId, $quantity, $userId, $reason, $expiryDate = null)
    {
        // Update or insert branch stock
        $existing = $this->db->table('branch_stock')
            ->where('branch_id', $branchId)
            ->where('item_id', $itemId)
            ->get()
            ->getRow();

        if ($existing) {
            $newQuantity = $existing->quantity + $quantity;
            $updateData = ['quantity' => $newQuantity, 'updated_at' => date('Y-m-d H:i:s')];
            if ($expiryDate) {
                $updateData['expiry_date'] = $expiryDate;
            }
            $this->db->table('branch_stock')
                ->where('branch_id', $branchId)
                ->where('item_id', $itemId)
                ->update($updateData);
        } else {
            $insertData = [
                'branch_id' => $branchId,
                'item_id' => $itemId,
                'quantity' => $quantity,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            if ($expiryDate) {
                $insertData['expiry_date'] = $expiryDate;
            }
            $this->db->table('branch_stock')->insert($insertData);
        }

        // Log the movement
        $this->logMovement($branchId, $itemId, $quantity, 'receive', $userId, $reason);
    }

    // ✅ Use stock (sales/cooking)
    public function useStock($branchId, $itemId, $quantity, $userId, $reason)
    {
        // Check current stock
        $current = $this->db->table('branch_stock')
            ->where('branch_id', $branchId)
            ->where('item_id', $itemId)
            ->get()
            ->getRow();

        if (!$current || $current->quantity < $quantity) {
            throw new \Exception('Insufficient stock');
        }

        $newQuantity = $current->quantity - $quantity;
        $this->db->table('branch_stock')
            ->where('branch_id', $branchId)
            ->where('item_id', $itemId)
            ->update(['quantity' => $newQuantity, 'updated_at' => date('Y-m-d H:i:s')]);

        // Log the movement
        $this->logMovement($branchId, $itemId, -$quantity, 'use', $userId, $reason);
    }

    // ✅ Transfer stock between branches
    public function transferStock($fromBranchId, $toBranchId, $itemId, $quantity, $userId, $reason)
    {
        // Check source branch stock
        $source = $this->db->table('branch_stock')
            ->where('branch_id', $fromBranchId)
            ->where('item_id', $itemId)
            ->get()
            ->getRow();

        if (!$source || $source->quantity < $quantity) {
            throw new \Exception('Insufficient stock in source branch');
        }

        // Reduce from source
        $newSourceQuantity = $source->quantity - $quantity;
        $this->db->table('branch_stock')
            ->where('branch_id', $fromBranchId)
            ->where('item_id', $itemId)
            ->update(['quantity' => $newSourceQuantity, 'updated_at' => date('Y-m-d H:i:s')]);

        // Add to destination
        $destination = $this->db->table('branch_stock')
            ->where('branch_id', $toBranchId)
            ->where('item_id', $itemId)
            ->get()
            ->getRow();

        if ($destination) {
            $newDestQuantity = $destination->quantity + $quantity;
            $this->db->table('branch_stock')
                ->where('branch_id', $toBranchId)
                ->where('item_id', $itemId)
                ->update(['quantity' => $newDestQuantity, 'updated_at' => date('Y-m-d H:i:s')]);
        } else {
            $this->db->table('branch_stock')->insert([
                'branch_id' => $toBranchId,
                'item_id' => $itemId,
                'quantity' => $quantity,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        
        // Log the movements
        $this->logMovement($fromBranchId, $itemId, -$quantity, 'transfer_out', $userId, $reason);
        $this->logMovement($toBranchId, $itemId, $quantity, 'transfer_in', $userId, $reason);
    }

    // ✅ Adjust stock (manual correction)
    public function adjustStock($branchId, $itemId, $newQuantity, $userId, $reason)
    {
        // Get current quantity for logging
        $current = $this->db->table('branch_stock')
            ->where('branch_id', $branchId)
            ->where('item_id', $itemId)
            ->get()
            ->getRow();

        $currentQuantity = $current ? $current->quantity : 0;
        $adjustment = $newQuantity - $currentQuantity;

        // Update stock
        if ($current) {
            $this->db->table('branch_stock')
                ->where('branch_id', $branchId)
                ->where('item_id', $itemId)
                ->update(['quantity' => $newQuantity, 'updated_at' => date('Y-m-d H:i:s')]);
        } else {
            $this->db->table('branch_stock')->insert([
                'branch_id' => $branchId,
                'item_id' => $itemId,
                'quantity' => $newQuantity,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Log the adjustment
        $this->logMovement($branchId, $itemId, $adjustment, 'adjust', $userId, $reason);
    }

    // ✅ Record spoilage/damage
    public function recordSpoilage($branchId, $itemId, $quantity, $userId, $reason)
    {
        // Check current stock
        $current = $this->db->table('branch_stock')
            ->where('branch_id', $branchId)
            ->where('item_id', $itemId)
            ->get()
            ->getRow();

        if (!$current || $current->quantity < $quantity) {
            throw new \Exception('Insufficient stock for spoilage recording');
        }

        $newQuantity = $current->quantity - $quantity;
        $this->db->table('branch_stock')
            ->where('branch_id', $branchId)
            ->where('item_id', $itemId)
            ->update(['quantity' => $newQuantity, 'updated_at' => date('Y-m-d H:i:s')]);

        // Log the spoilage
        $this->logMovement($branchId, $itemId, -$quantity, 'spoilage', $userId, $reason);
    }

    // Helper method to log stock movements
    private function logMovement($branchId, $itemId, $quantity, $type, $userId, $reason)
    {
        $this->db->table('stock_movements')->insert([
            'branch_id' => $branchId,
            'item_id' => $itemId,
            'quantity' => $quantity,
            'movement_type' => $type,
            'created_by' => $userId,
            'remarks' => $reason,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
