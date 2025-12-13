<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferModel extends Model
{
    protected $table = 'stock_movements';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    public function getTransfersWithDetails($branchId = null)
    {
        $db = \Config\Database::connect();
        
        // Get transfer_out movements (items being sent)
        $query = $db->table('stock_movements sm')
            ->select('sm.*, 
                     from_branch.name as from_branch_name,
                     i.name as item_name,
                     i.unit as item_unit,
                     u.username as created_by_name')
            ->join('branches from_branch', 'sm.branch_id = from_branch.id')
            ->join('items i', 'sm.item_id = i.id')
            ->join('users u', 'sm.created_by = u.id', 'left')
            ->where('sm.movement_type', 'transfer_out')
            ->orderBy('sm.created_at', 'DESC');

        if ($branchId) {
            $query->where('sm.branch_id', $branchId);
        }

        $transfers = $query->get()->getResultArray();

        // Get corresponding transfer_in records to determine status and destination branch
        foreach ($transfers as &$transfer) {
            // Find the corresponding transfer_in record
            // Match by item_id, quantity, and created after the transfer_out
            $transferIn = $db->table('stock_movements')
                ->where('movement_type', 'transfer_in')
                ->where('item_id', $transfer['item_id'])
                ->where('quantity', abs($transfer['quantity']))
                ->where('created_at >=', $transfer['created_at'])
                ->orderBy('created_at', 'ASC')
                ->limit(1)
                ->get()
                ->getRowArray();

            if ($transferIn) {
                // Get destination branch name
                $toBranch = $db->table('branches')
                    ->where('id', $transferIn['branch_id'])
                    ->get()
                    ->getRowArray();
                $transfer['to_branch_name'] = $toBranch['name'] ?? 'Unknown';
                $transfer['to_branch_id'] = $transferIn['branch_id'];
                $transfer['status'] = 'completed';
                $transfer['completed_at'] = $transferIn['created_at'];
            } else {
                // No corresponding transfer_in found - check if it's in transit or pending
                $daysDiff = (time() - strtotime($transfer['created_at'])) / (60 * 60 * 24);
                if ($daysDiff > 1) {
                    $transfer['status'] = 'in_transit';
                } else {
                    $transfer['status'] = 'pending';
                }
                $transfer['to_branch_name'] = 'Pending Assignment';
                $transfer['to_branch_id'] = null;
                $transfer['completed_at'] = null;
            }

            // Generate transfer ID
            $transfer['transfer_id'] = 'T' . str_pad($transfer['id'], 3, '0', STR_PAD_LEFT);
            
            // Format expected arrival (created_at + 1 day for pending/in_transit)
            $createdDate = new \DateTime($transfer['created_at']);
            $createdDate->modify('+1 day');
            $transfer['expected_arrival'] = $createdDate->format('M d, Y');
        }

        return $transfers;
    }

    public function getStats($branchId = null)
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('stock_movements')
            ->where('movement_type', 'transfer_out');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $allTransfers = $query->get()->getResultArray();
        
        $pending = 0;
        $inTransit = 0;
        $completedToday = 0;
        $today = date('Y-m-d');

        foreach ($allTransfers as $transfer) {
            $transferIn = $db->table('stock_movements')
                ->where('movement_type', 'transfer_in')
                ->where('item_id', $transfer['item_id'])
                ->where('quantity', abs($transfer['quantity']))
                ->where('created_at >=', $transfer['created_at'])
                ->get()
                ->getRowArray();

            if ($transferIn) {
                if (date('Y-m-d', strtotime($transferIn['created_at'])) === $today) {
                    $completedToday++;
                }
            } else {
                // Check if created more than 1 day ago = in transit, otherwise pending
                $daysDiff = (time() - strtotime($transfer['created_at'])) / (60 * 60 * 24);
                if ($daysDiff > 1) {
                    $inTransit++;
                } else {
                    $pending++;
                }
            }
        }

        return [
            'pending' => $pending,
            'in_transit' => $inTransit,
            'completed_today' => $completedToday
        ];
    }
}

