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

            $deliveryId = $this->request->getPost('delivery_id') ?? $this->request->getJSON(true)['delivery_id'] ?? null;
            $branchId = $this->request->getPost('branch_id') ?? $this->request->getJSON(true)['branch_id'] ?? null;

            $db = \Config\Database::connect();

            // Debug: Check what delivery records exist
            $allDeliveries = $this->deliveryModel->where('delivery_id', $deliveryId)->findAll();
            $debugInfo = [];
            foreach ($allDeliveries as $d) {
                $debugInfo[] = "ID: {$d['delivery_id']}, Status: {$d['status']}, Branch: {$d['branch_id']}";
            }
            
            // Check if delivery exists and is in correct status
            $delivery = $this->deliveryModel->where('delivery_id', $deliveryId)
                ->where('branch_id', $branchId)
                ->where('status', 'arrived')
                ->first();

            if (!$delivery) {
                $debugMsg = 'Delivery not found or not ready for approval. ';
                $debugMsg .= 'Looking for: delivery_id=' . $deliveryId . ', branch_id=' . $branchId . ', status=arrived. ';
                $debugMsg .= 'Found deliveries: ' . implode('; ', $debugInfo);
                return $this->response->setJSON(['success' => false, 'message' => $debugMsg]);
            }

            // Check if already approved
            if (!empty($delivery['approved_by'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Delivery already approved']);
            }

            // Update delivery status to 'delivered' (claimed by branch)
            $this->deliveryModel->update($delivery['id'], [
                'status' => 'delivered',
                'approved_by' => $user['id'],
                'approved_at' => date('Y-m-d H:i:s'),
                'arrival_time' => date('Y-m-d H:i:s')
            ]);

            // Get delivery items and add to inventory
            $deliveryItems = $db->table('delivery_items')
                ->where('delivery_id', $deliveryId)
                ->join('items', 'delivery_items.item_id = items.id', 'left')
                ->select('delivery_items.*, items.name')
                ->get()
                ->getResultArray();

            $debugItems = [];
            foreach ($deliveryItems as $item) {
                $debugItems[] = "ID: {$item['item_id']}, Name: {$item['name']}, Qty: {$item['quantity']}";
            }

            $addedItems = [];
            $errors = [];
            foreach ($deliveryItems as $item) {
                if ($item['item_id']) {
                    try {
                        // Check if item already exists in branch stock
                        $existingStock = $db->table('branch_stock')
                            ->where('branch_id', $branchId)
                            ->where('item_id', $item['item_id'])
                            ->get()
                            ->getRow();

                        if ($existingStock) {
                            // Update existing stock
                            $newQuantity = $existingStock->quantity + $item['quantity'];
                            $db->table('branch_stock')
                                ->where('id', $existingStock->id)
                                ->update([
                                    'quantity' => $newQuantity,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);
                        } else {
                            // Insert new stock record
                            $db->table('branch_stock')->insert([
                                'branch_id' => $branchId,
                                'item_id' => $item['item_id'],
                                'quantity' => $item['quantity'],
                                'expiry_date' => $item['expiry_date'] ?? null,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        }

                        // Record stock movement
                        $db->table('stock_movements')->insert([
                            'branch_id' => $branchId,
                            'item_id' => $item['item_id'],
                            'movement_type' => 'in',
                            'quantity' => $item['quantity'],
                            'reference_type' => 'delivery',
                            'reference_id' => $deliveryId,
                            'notes' => "Delivery received: {$item['quantity']} {$item['name']} from {$delivery['driver']}",
                            'created_by' => $user['id'],
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        $addedItems[] = "{$item['quantity']} {$item['name']}";
                    } catch (\Exception $e) {
                        $errors[] = "Error adding {$item['name']}: " . $e->getMessage();
                    }
                } else {
                    $errors[] = "No item_id for: {$item['item_name']}";
                }
            }

            $itemsJoined = !empty($addedItems) ? implode(', ', $addedItems) : 'items';

            // Update corresponding purchase order status to 'delivered'
            if ($delivery['purchase_order_id']) {
                $purchaseOrderModel = new \App\Models\PurchaseOrderModel();
                $purchaseOrderModel->update($delivery['purchase_order_id'], [
                    'status' => 'delivered'
                ]);
            }

            $debugMsg = "Delivery claimed! ";
            $debugMsg .= "Found " . count($deliveryItems) . " delivery items: " . implode('; ', $debugItems) . ". ";
            $debugMsg .= "Added to inventory: {$itemsJoined}. ";
            if (!empty($errors)) {
                $debugMsg .= "Errors: " . implode('; ', $errors);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $debugMsg
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

            // First, get basic deliveries without complex joins
            $query = $db->table('deliveries d')
                ->select('d.*, COALESCE(b.name, CONCAT("Branch ID: ", d.branch_id)) as branch_name')
                ->join('branches b', 'd.branch_id = b.id', 'left')
                ->orderBy('d.scheduled_time', 'ASC');

            // Filter by branch if provided
            if ($branchId) {
                $query->where('d.branch_id', $branchId);
            }

            $deliveries = $query->get()->getResultArray();

            // Load items for each delivery (exactly like Purchase Orders does it)
            foreach ($deliveries as &$delivery) {
                $delivery['items'] = $db->table('delivery_items di')
                    ->select('di.*, i.name as item_name_from_db, i.unit as item_unit_from_db')
                    ->join('items i', 'di.item_id = i.id', 'left')
                    ->where('di.delivery_id', $delivery['delivery_id'])
                    ->get()
                    ->getResultArray();

                // Add approve permission flag for branch users
                $delivery['can_approve'] = $branchId && $delivery['branch_id'] == $branchId && $delivery['status'] === 'arrived';
            }

            return $deliveries;
        } catch (\Exception $e) {
            // Return empty array if database tables don't exist or query fails
            return [];
        }
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
