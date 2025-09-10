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

    // ===============================
    // 📦 Branch-only: Inventory Page
    // ===============================

    // ✅ Add item (Receive stock from supplier)
    public function addItem()
    {
        try {
            $itemName = $this->request->getPost('item_name');
            $item = $this->db->table('items')
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
            $itemName = $this->request->getPost('item_name');
            $item = $this->db->table('items')
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
            $itemName = $this->request->getPost('item_name');
            $item = $this->db->table('items')
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

    public function binventory(): string
    {
        // Get current user's branch
        $user = session()->get('user');
        if (!$user || !isset($user['branch_id']) || $user['branch_id'] === null) {
            return redirect()->to(base_url('bdashboard'))->with('error', 'No branch assigned to your account');
        }

        $branchId = $user['branch_id'];
        $db = \Config\Database::connect();

        // Get branch information
        $branch = $db->table('branches')
            ->where('id', $branchId)
            ->get()
            ->getRow();

        if (!$branch) {
            return redirect()->to(base_url('bdashboard'))->with('error', 'Branch not found');
        }

        // Join items with branch_stock to get quantities for this specific branch
        $inventory = $db->table('items')
            ->select('items.id as item_id, items.name as item_name, items.unit, items.reorder_level, branch_stock.quantity')
            ->join('branch_stock', 'items.id = branch_stock.item_id AND branch_stock.branch_id = ' . $branchId, 'left')
            ->where('items.status', 'active')
            ->get()
            ->getResultArray();

        // Format data for the view
        $formattedInventory = [];
        foreach ($inventory as $item) {
            $formattedInventory[] = [
                'item_id'       => $item['item_id'],
                'item_name'     => $item['item_name'],
                'category'      => 'General', // placeholder, add category column if needed
                'quantity'      => $item['quantity'] ?? 0,
                'unit'          => $item['unit'],
                'expiry_date'   => null, // add when you have expiry column
                'reorder_level' => $item['reorder_level']
            ];
        }

        // Calculate stats for this branch
        $totalItems = count($formattedInventory);
        $lowStock = 0;
        $outOfStock = 0;
        $nearExpiry = 0;

        foreach ($formattedInventory as $item) {
            $quantity = $item['quantity'] ?? 0;
            if ($quantity == 0) {
                $outOfStock++;
            } elseif ($quantity <= $item['reorder_level']) {
                $lowStock++;
            }
            // Near expiry logic can be added later
        }

        return view('Branch-only/binventory', [
            'branch'      => $branch,
            'inventory'   => $formattedInventory,
            'totalItems'  => $totalItems,
            'lowStock'    => $lowStock,
            'outOfStock'  => $outOfStock,
            'nearExpiry'  => $nearExpiry
        ]);
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
            ->select('items.id as item_id, items.name as item_name, items.unit, items.reorder_level, branch_stock.quantity')
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
            // Near expiry logic can be added later
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
