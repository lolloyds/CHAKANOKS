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

    public function receiveStock($branchId, $itemId, $quantity, $userId, $reason, $expiryDate = null)
    {
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

        $this->logMovement($branchId, $itemId, $quantity, 'receive', $userId, $reason);
    }

    public function useStock($branchId, $itemId, $quantity, $userId, $reason)
    {
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

        $this->logMovement($branchId, $itemId, -$quantity, 'use', $userId, $reason);
    }

    public function transferStock($fromBranchId, $toBranchId, $itemId, $quantity, $userId, $reason)
    {
        $source = $this->db->table('branch_stock')
            ->where('branch_id', $fromBranchId)
            ->where('item_id', $itemId)
            ->get()
            ->getRow();

        if (!$source || $source->quantity < $quantity) {
            throw new \Exception('Insufficient stock in source branch');
        }

        $newSourceQuantity = $source->quantity - $quantity;
        $this->db->table('branch_stock')
            ->where('branch_id', $fromBranchId)
            ->where('item_id', $itemId)
            ->update(['quantity' => $newSourceQuantity, 'updated_at' => date('Y-m-d H:i:s')]);

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

        
        $this->logMovement($fromBranchId, $itemId, -$quantity, 'transfer_out', $userId, $reason);
        $this->logMovement($toBranchId, $itemId, $quantity, 'transfer_in', $userId, $reason);
    }

    public function adjustStock($branchId, $itemId, $newQuantity, $userId, $reason)
    {
        $current = $this->db->table('branch_stock')
            ->where('branch_id', $branchId)
            ->where('item_id', $itemId)
            ->get()
            ->getRow();

        $currentQuantity = $current ? $current->quantity : 0;
        $adjustment = $newQuantity - $currentQuantity;

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

        $this->logMovement($branchId, $itemId, $adjustment, 'adjust', $userId, $reason);
    }

    public function recordSpoilage($branchId, $itemId, $quantity, $userId, $reason)
    {
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

        $this->logMovement($branchId, $itemId, -$quantity, 'spoilage', $userId, $reason);
    }

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

        $this->checkStockAlerts($branchId, $itemId);
    }

    public function checkStockAlerts($branchId, $itemId)
    {
        $stock = $this->db->table('branch_stock')
            ->where('branch_id', $branchId)
            ->where('item_id', $itemId)
            ->get()
            ->getRow();

        $item = $this->db->table('items')
            ->where('id', $itemId)
            ->where('status', 'active')
            ->get()
            ->getRow();

        if (!$item) return;

        $quantity = $stock ? $stock->quantity : 0;
        $reorderLevel = $item->reorder_level ?? 0;

        if ($quantity == 0) {
            $this->createAlert([
                'branch_id' => $branchId,
                'item_id' => $itemId,
                'alert_type' => 'out_of_stock',
                'title' => 'Out of Stock Alert',
                'message' => "{$item->name} is completely out of stock at this branch.",
                'severity' => 'critical'
            ]);
        }
        elseif ($quantity > 0 && $quantity <= $reorderLevel) {
            $this->createAlert([
                'branch_id' => $branchId,
                'item_id' => $itemId,
                'alert_type' => 'low_stock',
                'title' => 'Low Stock Alert',
                'message' => "{$item->name} is running low (Current: {$quantity} {$item->unit}, Reorder Level: {$reorderLevel} {$item->unit}).",
                'severity' => 'high'
            ]);
        }

        if ($item->perishable && $stock && $stock->expiry_date) {
            $expiryDate = strtotime($stock->expiry_date);
            $now = time();
            $daysUntilExpiry = floor(($expiryDate - $now) / (60 * 60 * 24));

            if ($daysUntilExpiry <= 7 && $daysUntilExpiry > 0) {
                $this->createAlert([
                    'branch_id' => $branchId,
                    'item_id' => $itemId,
                    'alert_type' => 'near_expiry',
                    'title' => 'Near Expiry Alert',
                    'message' => "{$item->name} expires in {$daysUntilExpiry} day(s) ({$stock->expiry_date}). Current stock: {$quantity} {$item->unit}.",
                    'severity' => 'medium'
                ]);
            } elseif ($daysUntilExpiry <= 0) {
                $this->createAlert([
                    'branch_id' => $branchId,
                    'item_id' => $itemId,
                    'alert_type' => 'expired',
                    'title' => 'Expired Item Alert',
                    'message' => "{$item->name} has expired ({$stock->expiry_date}). Current stock: {$quantity} {$item->unit}.",
                    'severity' => 'critical'
                ]);
            }
        }
    }

    private function createAlert($data)
    {
        $alertModel = new \App\Models\AlertModel();
        $alertModel->createAlert($data);
    }

    public function getBranchAlerts($branchId)
    {
        $alertModel = new \App\Models\AlertModel();
        return $alertModel->getActiveAlerts($branchId);
    }

    public function getAlertCounts($branchId = null)
    {
        $alertModel = new \App\Models\AlertModel();
        return $alertModel->getAlertCounts($branchId);
    }

    public function acknowledgeAlert($alertId, $userId)
    {
        $alertModel = new \App\Models\AlertModel();
        return $alertModel->acknowledgeAlert($alertId, $userId);
    }

    public function checkAllAlerts($branchId = null)
    {
        $query = $this->db->table('items')
            ->select('items.id as item_id, items.name, items.reorder_level, items.perishable, branch_stock.quantity, branch_stock.expiry_date, branch_stock.branch_id')
            ->join('branch_stock', 'items.id = branch_stock.item_id', 'left')
            ->where('items.status', 'active');

        if ($branchId) {
            $query->where('branch_stock.branch_id', $branchId);
        }

        $items = $query->get()->getResultArray();

        foreach ($items as $item) {
            $this->checkStockAlerts($item['branch_id'], $item['item_id']);
        }
    }
}
