<?php

namespace App\Controllers;

use App\Services\InventoryService;

class Inventory extends BaseController
{
    protected $inventoryService;

    public function __construct()
    {
        $this->inventoryService = new InventoryService();
    }

    // Main inventory page - role-based display
    public function inventory()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $data = [];

        // If branch user, get branch-specific inventory data
        if (in_array($user['role'], ['Inventory Staff', 'Branch Manager']) && isset($user['branch_id'])) {
            $branchId = $user['branch_id'];
            $db = \Config\Database::connect();

            // Get branch information
            $branch = $db->table('branches')
                ->where('id', $branchId)
                ->get()
                ->getRow();

            if ($branch) {
                // Get inventory stats for this specific branch
                $inventory = $db->table('items')
                    ->select('items.id as item_id, items.name as item_name, items.unit, items.reorder_level, items.expiry_date, branch_stock.quantity')
                    ->join('branch_stock', 'items.id = branch_stock.item_id AND branch_stock.branch_id = ' . $branchId, 'left')
                    ->where('items.status', 'active')
                    ->get()
                    ->getResultArray();

                // Calculate stats for this branch
                $totalItems = count($inventory);
                $lowStock = 0;
                $outOfStock = 0;
                $nearExpiry = 0;

                foreach ($inventory as $item) {
                    $quantity = $item['quantity'] ?? 0;
                    if ($quantity == 0) {
                        $outOfStock++;
                    } elseif ($quantity <= $item['reorder_level']) {
                        $lowStock++;
                    }
                    if (isset($item['expiry_date']) && $item['expiry_date'] && $item['expiry_date'] <= date('Y-m-d', strtotime('+7 days'))) {
                        $nearExpiry++;
                    }
                }

                $data = [
                    'branch' => $branch,
                    'totalItems' => $totalItems,
                    'lowStock' => $lowStock,
                    'outOfStock' => $outOfStock,
                    'nearExpiry' => $nearExpiry,
                    'inventory' => $inventory,
                    'isBranchUser' => true
                ];
            }
        } else {
            // Central office user - show overview stats
            $data['isBranchUser'] = false;
        }

        return view('inventory', $data);
    }

    // ===============================
    // 📦 Branch-only: Inventory Page
    // ===============================

    // ✅ Add item (Receive stock from supplier)
    public function addItem()
    {
        try {
            $db = \Config\Database::connect();
            $itemName = $this->request->getPost('item_name');
            $item = $db->table('items')
                ->where('LOWER(name)', strtolower($itemName))
                ->where('status', 'active')
                ->get()
                ->getRow();

            if (!$item) {
                throw new \Exception('Item not found: ' . $itemName);
            }

            $this->inventoryService->receiveStock(
                $this->request->getPost('branch_id'),
                $item->id,
                $this->request->getPost('quantity'),
                session()->get('user')['id'],
                'Delivery from supplier'
            );

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Item added to inventory!']);
            }

            return redirect()->back()->with('success', 'Item added to inventory!');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ✅ Use item (Cooking / Sales)
    public function useItem()
    {
        try {
            $db = \Config\Database::connect();
            $itemName = $this->request->getPost('item_name');
            $item = $db->table('items')
                ->where('LOWER(name)', strtolower($itemName))
                ->where('status', 'active')
                ->get()
                ->getRow();

            if (!$item) {
                throw new \Exception('Item not found: ' . $itemName);
            }

            $this->inventoryService->useStock(
                $this->request->getPost('branch_id'),
                $item->id,
                $this->request->getPost('quantity'),
                session()->get('user')['id'],
                'Used for sales/cooking'
            );

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Item usage recorded!']);
            }

            return redirect()->back()->with('success', 'Item usage recorded!');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ✅ Report damage or expiry
    public function reportDamage()
    {
        try {
            $db = \Config\Database::connect();
            $itemName = $this->request->getPost('item_name');
            $item = $db->table('items')
                ->where('LOWER(name)', strtolower($itemName))
                ->where('status', 'active')
                ->get()
                ->getRow();

            if (!$item) {
                throw new \Exception('Item not found: ' . $itemName);
            }

            $this->inventoryService->recordSpoilage(
                $this->request->getPost('branch_id'),
                $item->id,
                $this->request->getPost('quantity'),
                session()->get('user')['id'],
                'Expired or damaged'
            );

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Spoilage reported!']);
            }

            return redirect()->back()->with('success', 'Spoilage reported!');
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // ===============================
    // 🏢 Central Office Functions
    // ===============================

    // ✅ Transfer stock between branches
    public function transferItem()
    {
        $this->inventoryService->transferStock(
            $this->request->getPost('from_branch_id'),
            $this->request->getPost('to_branch_id'),
            $this->request->getPost('item_id'),
            $this->request->getPost('quantity'),
            session()->get('user')['id'],
            'Branch transfer'
        );

        return redirect()->back()->with('success', 'Stock transferred!');
    }

    // ✅ Manual adjustment (corrections)
    public function adjustItem()
    {
        $this->inventoryService->adjustStock(
            $this->request->getPost('branch_id'),
            $this->request->getPost('item_id'),
            $this->request->getPost('quantity'),
            session()->get('user')['id'],
            'Manual adjustment'
        );

        return redirect()->back()->with('success', 'Stock adjusted!');
    }



    public function bdashboard(): string
    {
        // Get current user's branch
        $user = session()->get('user');
        if (!$user || !isset($user['branch_id']) || $user['branch_id'] === null) {
            // If user doesn't have a branch assigned, redirect to main dashboard
            return redirect()->to(base_url('dashboard'))->with('error', 'No branch assigned to your account');
        }

        $branchId = $user['branch_id'];
        $db = \Config\Database::connect();

        // Get branch information
        $branch = $db->table('branches')
            ->where('id', $branchId)
            ->get()
            ->getRow();

        if (!$branch) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Branch not found');
        }

        // Get inventory stats for this specific branch
        $inventory = $db->table('items')
            ->select('items.id as item_id, items.name as item_name, items.unit, items.reorder_level, items.expiry_date, branch_stock.quantity')
            ->join('branch_stock', 'items.id = branch_stock.item_id AND branch_stock.branch_id = ' . $branchId, 'left')
            ->where('items.status', 'active')
            ->get()
            ->getResultArray();

        // Calculate stats for this branch
        $totalItems = count($inventory);
        $lowStock = 0;
        $outOfStock = 0;
        $nearExpiry = 0;

        foreach ($inventory as $item) {
            $quantity = $item['quantity'] ?? 0;
            if ($quantity == 0) {
                $outOfStock++;
            } elseif ($quantity <= $item['reorder_level']) {
                $lowStock++;
            }
            if (isset($item['expiry_date']) && $item['expiry_date'] && $item['expiry_date'] <= date('Y-m-d', strtotime('+7 days'))) {
                $nearExpiry++;
            }
        }

        return view('Branch-only/bdashboard', [
            'branch'      => $branch,
            'totalItems'  => $totalItems,
            'lowStock'    => $lowStock,
            'outOfStock'  => $outOfStock,
            'nearExpiry'  => $nearExpiry,
            'inventory'   => $inventory
        ]);
    }

}
