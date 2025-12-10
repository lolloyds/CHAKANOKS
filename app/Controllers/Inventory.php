<?php

namespace App\Controllers;

use App\Services\InventoryService;
use App\Models\DeliveryModel;
use App\Models\ItemModel;

class Inventory extends BaseController
{
    protected $inventoryService;
    protected $deliveryModel;

    public function __construct()
    {
        $this->inventoryService = new InventoryService();
        $this->deliveryModel = new DeliveryModel();
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
                    ->select('items.id as item_id, items.name as item_name, items.unit, items.reorder_level, branch_stock.expiry_date, branch_stock.quantity')
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



    public function deliveries()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $data = [];

        // Get user role for access control
        $data['userRole'] = $user['role'];

        // Get branch deliveries for branch staff, all deliveries for central office
        if (in_array($user['role'], ['Inventory Staff', 'Branch Manager']) && isset($user['branch_id'])) {
            // Branch user - show only their branch deliveries
            $deliveries = $this->getBranchDeliveries($user['branch_id']);
            $data['deliveries'] = $deliveries;
            $data['branch_name'] = 'Your Branch';
            $data['isBranchUser'] = true;
        } else {
            // Central office - show all deliveries
            $deliveries = $this->getAllDeliveries();
            $data['deliveries'] = $deliveries;
            $data['isBranchUser'] = false;

            // Calculate stats
            $data['scheduled'] = $this->countDeliveriesByStatus('scheduled');
            $data['inTransit'] = $this->countDeliveriesByStatus('in_transit');
            $data['delivered'] = $this->countDeliveriesByStatus('delivered');
        }

        return view('deliveries', $data);
    }

    public function approveDelivery()
    {
        try {
            $user = session()->get('user');
            if (!$user || !in_array($user['role'], ['Inventory Staff', 'Branch Manager'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }

            $deliveryId = $this->request->getPost('delivery_id');
            $branchId = $this->request->getPost('branch_id');

            $db = \Config\Database::connect();

            // Check if delivery exists and is in correct status
            $delivery = $this->deliveryModel->where('delivery_id', $deliveryId)
                ->where('branch_id', $branchId)
                ->where('status', 'delivered')
                ->first();

            if (!$delivery) {
                return $this->response->setJSON(['success' => false, 'message' => 'Delivery not found or not ready for approval']);
            }

            // Check if already approved
            if (!empty($delivery['approved_by'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Delivery already approved']);
            }

            // Update delivery status to 'received'
            $this->deliveryModel->update($delivery['id'], [
                'status' => 'received',
                'approved_by' => $user['id'],
                'approved_at' => date('Y-m-d H:i:s'),
                'arrival_time' => date('Y-m-d H:i:s')
            ]);

            // Get delivery items and add to inventory
            $deliveryItems = $db->table('delivery_items')
                ->where('delivery_id', $deliveryId)
                ->join('items', 'delivery_items.item_id = items.id')
                ->select('delivery_items.*, items.name')
                ->get()
                ->getResultArray();

            $addedItems = [];
            foreach ($deliveryItems as $item) {
                if ($item['item_id']) {
                    $this->inventoryService->receiveStock(
                        $branchId,
                        $item['item_id'],
                        $item['quantity'],
                        $user['id'],
                        "Delivery received: {$item['quantity']} {$item['name']} from {$delivery['driver']}"
                    );
                    $addedItems[] = "{$item['quantity']} {$item['name']}";
                }
            }

            $itemsJoined = !empty($addedItems) ? implode(', ', $addedItems) : 'items';

            return $this->response->setJSON([
                'success' => true,
                'message' => "Delivery approved! Added to inventory: {$itemsJoined}"
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Database-driven delivery methods
    private function getBranchDeliveries($branchId)
    {
        return $this->getDeliveriesFromDatabase($branchId);
    }

    private function getAllDeliveries()
    {
        return $this->getDeliveriesFromDatabase(null);
    }

    private function countDeliveriesByStatus($status)
    {
        try {
            return $this->deliveryModel->where('status', $status)->countAllResults();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getDeliveriesFromDatabase($branchId = null)
    {
        try {
            $db = \Config\Database::connect();

            // Build query to get deliveries with branch names and all delivery items
            $query = $db->table('deliveries d')
                ->select('d.*, b.name as branch_name, GROUP_CONCAT(CONCAT(di.quantity, " ", i.name) SEPARATOR ", ") as items')
                ->join('branches b', 'd.branch_id = b.id')
                ->join('delivery_items di', 'd.delivery_id = di.delivery_id', 'left')
                ->join('items i', 'di.item_id = i.id', 'left')
                ->groupBy('d.id')
                ->orderBy('d.scheduled_time', 'ASC');

            // Filter by branch if provided
            if ($branchId) {
                $query->where('d.branch_id', $branchId);
            }

            $deliveries = $query->get()->getResultArray();

            // Add approve permission flag for branch users
            foreach ($deliveries as &$delivery) {
                $delivery['can_approve'] = $branchId && $delivery['branch_id'] == $branchId && $delivery['status'] === 'delivered';
                // Ensure items exists and is not null
                $delivery['items'] = $delivery['items'] ?: 'No items listed';
            }

            return $deliveries;
        } catch (\Exception $e) {
            // Fall back to sample data if tables don't exist yet
            return $this->getFallbackDeliveries($branchId);
        }
    }

    private function getFallbackDeliveries($branchId = null)
    {
        // Dummy deliveries for testing if database is not set up yet
        $deliveries = [
            [
                'id' => 'DLV-001',
                'delivery_id' => 'DLV-001',
                'branch_id' => 1,
                'branch_name' => 'Chakanoks Davao - Bajada',
                'items' => '50 Roasted Chickens, 30kg Rice',
                'driver' => 'Juan Dela Cruz',
                'status' => 'scheduled',
                'scheduled_time' => date('Y-m-d H:i:s', strtotime('+1 day')),
                'can_approve' => !empty($branchId) && $branchId == 1
            ],
            [
                'id' => 'DLV-002',
                'delivery_id' => 'DLV-002',
                'branch_id' => 2,
                'branch_name' => 'Chakanoks Davao - Matina',
                'items' => '25 Roasted Chickens, 10kg Veggies',
                'driver' => 'Pedro Santos',
                'status' => 'in_transit',
                'scheduled_time' => date('Y-m-d H:i:s'),
                'can_approve' => !empty($branchId) && $branchId == 2
            ],
            [
                'id' => 'DLV-003',
                'delivery_id' => 'DLV-003',
                'branch_id' => 3,
                'branch_name' => 'Chakanoks Davao - Toril',
                'items' => '40 Roasted Chickens, 20kg Rice',
                'driver' => 'Carlos Reyes',
                'status' => 'delivered',
                'scheduled_time' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'can_approve' => !empty($branchId) && $branchId == 3
            ]
        ];

        return $branchId ? array_filter($deliveries, function($d) use ($branchId) {
            return $d['branch_id'] == $branchId;
        }) : $deliveries;
    }



    // ===============================
    // 🚨 Alert Management
    // ===============================

    public function getAlerts()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $branchId = in_array($user['role'], ['Inventory Staff', 'Branch Manager']) ? $user['branch_id'] : null;

        try {
            $alerts = $this->inventoryService->getBranchAlerts($branchId);
            $alertCounts = $this->inventoryService->getAlertCounts($branchId);

            return $this->response->setJSON([
                'success' => true,
                'alerts' => $alerts,
                'counts' => $alertCounts
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function acknowledgeAlert()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $alertId = $this->request->getPost('alert_id');

        try {
            $this->inventoryService->acknowledgeAlert($alertId, $user['id']);
            return $this->response->setJSON(['success' => true, 'message' => 'Alert acknowledged']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function checkAlerts()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $branchId = in_array($user['role'], ['Inventory Staff', 'Branch Manager']) ? $user['branch_id'] : null;

        try {
            $this->inventoryService->checkAllAlerts($branchId);
            return $this->response->setJSON(['success' => true, 'message' => 'Alerts checked']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $itemModel = new ItemModel();
        $item = $itemModel->find($id);
        if ($this->request->getMethod() === 'post') {
            $data = [
                'item_name'   => $this->request->getPost('item_name'),
                'category'    => $this->request->getPost('category'),
                'quantity'    => $this->request->getPost('quantity'),
                'unit'        => $this->request->getPost('unit'),
                'expiry_date' => $this->request->getPost('expiry_date'),
            ];
            $itemModel->update($id, $data);
            return redirect()->to('/inventory');
        }
        return view('inventory/edit', ['item' => $item]);
    }

    public function remove($id)
    {
        $itemModel = new ItemModel();
        $itemModel->delete($id);
        return redirect()->to('/inventory');
    }

}
