<?php

namespace App\Controllers;

use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\PurchaseRequestModel;
use App\Models\SupplierModel;
use App\Models\ItemModel;
use App\Models\BranchModel;
use App\Models\DeliveryModel;
use App\Models\DeliveryItemModel;
use App\Services\InventoryService;

class PurchaseOrder extends BaseController
{
    protected $purchaseOrderModel;
    protected $purchaseOrderItemModel;
    protected $purchaseRequestModel;
    protected $supplierModel;
    protected $itemModel;
    protected $branchModel;
    protected $deliveryModel;
    protected $deliveryItemModel;
    protected $inventoryService;

    public function __construct()
    {
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->purchaseOrderItemModel = new PurchaseOrderItemModel();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->supplierModel = new SupplierModel();
        $this->itemModel = new ItemModel();
        $this->branchModel = new BranchModel();
        $this->deliveryModel = new DeliveryModel();
        $this->deliveryItemModel = new DeliveryItemModel();
        $this->inventoryService = new InventoryService();
    }

    /**
     * Display purchase orders page
     */
    public function index()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $branchId = null;
        $supplierId = null;
        
        if (in_array($user['role'], ['Inventory Staff', 'Branch Manager']) && isset($user['branch_id'])) {
            $branchId = $user['branch_id'];
        }
        
        // For Supplier role, filter by supplier (if supplier_id is stored in user session or can be determined)
        // For now, Supplier role users can see all POs assigned to any supplier
        // In a production system, you'd link users to suppliers via a user_supplier_id field

        $data = [
            'orders' => $this->purchaseOrderModel->getOrdersWithDetails($branchId, $supplierId),
            'stats' => $this->purchaseOrderModel->getStats($branchId, $supplierId),
            'suppliers' => $this->supplierModel->where('status', 'Active')->findAll(),
            'branches' => $this->branchModel->findAll(),
            'items' => $this->itemModel->where('status', 'active')->findAll(),
            'approvedRequests' => $this->purchaseRequestModel->where('status', 'approved')->findAll(),
            'userRole' => $user['role'],
            'branchId' => $branchId,
            'supplierId' => $supplierId,
        ];

        // Load items for each order with proper joins to ensure item data
        $db = \Config\Database::connect();
        foreach ($data['orders'] as &$order) {
            $order['items'] = $db->table('purchase_order_items poi')
                ->select('poi.*, i.name as item_name_from_db, i.unit as item_unit_from_db')
                ->join('items i', 'poi.item_id = i.id', 'left')
                ->where('poi.purchase_order_id', $order['id'])
                ->get()
                ->getResultArray();

            // Ensure each item has a name (prefer the stored item_name, fallback to DB name)
            foreach ($order['items'] as &$item) {
                if (empty($item['item_name']) && !empty($item['item_name_from_db'])) {
                    $item['item_name'] = $item['item_name_from_db'];
                }
                if (empty($item['unit']) && !empty($item['item_unit_from_db'])) {
                    $item['unit'] = $item['item_unit_from_db'];
                }
            }
        }

        return view('purchase-orders', $data);
    }

    /**
     * Create new purchase order
     */
    public function create()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Create purchase order
            $poId = $this->purchaseOrderModel->generatePoId();
            $orderData = [
                'po_id' => $poId,
                'purchase_request_id' => $this->request->getPost('purchase_request_id') ?: null,
                'supplier_id' => $this->request->getPost('supplier_id'),
                'branch_id' => $this->request->getPost('branch_id') ?: null,
                'order_date' => $this->request->getPost('order_date') ?: date('Y-m-d'),
                'expected_delivery_date' => $this->request->getPost('expected_delivery_date'),
                'status' => 'pending',
                'notes' => $this->request->getPost('notes'),
                'created_by' => $user['id'],
            ];

            $purchaseOrderId = $this->purchaseOrderModel->insert($orderData);

            // Add items and calculate total
            $items = $this->request->getPost('items');
            $totalCost = 0;
            if (is_array($items)) {
                foreach ($items as $item) {
                    if (!empty($item['item_name']) && !empty($item['quantity'])) {
                        // Try to find item by name
                        $itemRecord = $this->itemModel
                            ->where('LOWER(name)', strtolower($item['item_name']))
                            ->first();

                        $unitPrice = floatval($item['unit_price'] ?? 0);
                        $quantity = floatval($item['quantity']);
                        $totalPrice = $unitPrice * $quantity;
                        $totalCost += $totalPrice;

                        $this->purchaseOrderItemModel->insert([
                            'purchase_order_id' => $purchaseOrderId,
                            'item_id' => $itemRecord ? $itemRecord['id'] : null,
                            'item_name' => $item['item_name'],
                            'quantity' => $quantity,
                            'unit' => $item['unit'] ?? ($itemRecord ? $itemRecord['unit'] : 'pcs'),
                            'unit_price' => $unitPrice,
                            'total_price' => $totalPrice,
                            'notes' => $item['notes'] ?? null,
                        ]);
                    }
                }
            }

            // Update total cost
            $this->purchaseOrderModel->update($purchaseOrderId, ['total_cost' => $totalCost]);

            // If created from purchase request, mark PR as converted
            if ($orderData['purchase_request_id']) {
                $this->purchaseRequestModel->update($orderData['purchase_request_id'], [
                    'status' => 'converted'
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Purchase order created successfully!',
                'po_id' => $poId
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update purchase order status
     */
    public function updateStatus($id)
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $order = $this->purchaseOrderModel->find($id);
            if (!$order) {
                return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
            }

            $newStatus = $this->request->getPost('status') ?? $this->request->getJSON(true)['status'] ?? null;
            $validStatuses = ['pending', 'approved', 'pending_delivery_schedule', 'scheduled_for_delivery', 'ordered', 'in_transit', 'delayed', 'arriving', 'delivered', 'delivered_to_branch', 'completed', 'cancelled'];

            if (!in_array($newStatus, $validStatuses)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
            }

            $updateData = ['status' => $newStatus];

            // If approving, record approver
            if ($newStatus === 'approved' && in_array($user['role'], ['Central Office Admin', 'System Administrator'])) {
                $updateData['approved_by'] = $user['id'];
                $updateData['approved_at'] = date('Y-m-d H:i:s');
            }

            $this->purchaseOrderModel->update($id, $updateData);

            // If status changed to in_transit, create delivery record
            if ($newStatus === 'in_transit') {
                $this->createDeliveryFromPO($order, $user);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Purchase order status updated!'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update delivery timeline (used by Logistics Coordinators and Suppliers)
     */
    public function updateDeliveryTimeline($id)
    {
        $user = session()->get('user');
        if (!$user || !in_array($user['role'], ['Logistics Coordinator', 'Supplier'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized - Logistics Coordinator or Supplier access only']);
        }

        try {
            $order = $this->purchaseOrderModel->find($id);
            if (!$order) {
                return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
            }

            $newStatus = $this->request->getPost('status') ?? $this->request->getJSON(true)['status'] ?? null;

            // Define allowed status transitions based on user role
            if ($user['role'] === 'Supplier') {
                // Suppliers can manage delivery statuses
                $validStatuses = ['in_transit', 'delayed', 'arrived'];
                $allowedCurrentStatuses = ['scheduled_for_delivery', 'in_transit', 'delayed'];

                if (!in_array($order['status'], $allowedCurrentStatuses)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Cannot update delivery status from current status: ' . $order['status']
                    ]);
                }
            } elseif ($user['role'] === 'Logistics Coordinator') {
                // Logistics Coordinators can update various statuses
                $validStatuses = ['scheduled_for_delivery', 'in_transit', 'delayed', 'arrived'];
            } else {
                // Fallback for any other authorized role
                $validStatuses = ['in_transit'];
            }

            if (!in_array($newStatus, $validStatuses)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid status for delivery timeline update. Role: ' . $user['role'] . ', Valid statuses: ' . implode(', ', $validStatuses) . ', Requested status: ' . $newStatus
                ]);
            }

            // Get optional delivery notes/updates
            $deliveryNotes = $this->request->getPost('delivery_notes') ?? $this->request->getJSON(true)['delivery_notes'] ?? null;
            $expectedArrivalDate = $this->request->getPost('expected_arrival_date') ?? $this->request->getJSON(true)['expected_arrival_date'] ?? null;

            $updateData = [
                'status' => $newStatus
            ];

            // Update expected delivery date if provided
            if ($expectedArrivalDate) {
                $updateData['expected_delivery_date'] = $expectedArrivalDate;
            }

            // Add delivery notes to existing notes
            if ($deliveryNotes) {
                $existingNotes = $order['notes'] ?? '';
                $timestamp = date('Y-m-d H:i:s');
                $updateData['notes'] = $existingNotes . "\n\n[Logistics Update - {$timestamp}]: " . $deliveryNotes;
            }

            $this->purchaseOrderModel->update($id, $updateData);

            // If status changed to 'in_transit', create a delivery record
            if ($newStatus === 'in_transit') {
                $this->createDeliveryFromPO($order, $user);
            }

            // Update corresponding delivery record status if it exists
            $this->updateDeliveryStatus($id, $newStatus);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Delivery timeline updated successfully!'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Schedule PO for delivery (used by Logistics Coordinator)
     */
    public function scheduleDelivery($id)
    {
        $user = session()->get('user');
        if (!$user || !in_array($user['role'], ['Logistics Coordinator', 'Supplier'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized - Logistics Coordinator or Supplier access only']);
        }

        try {
            $order = $this->purchaseOrderModel->find($id);
            if (!$order) {
                return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
            }

            // Only allow updating if status is 'pending_delivery_schedule'
            if ($order['status'] !== 'pending_delivery_schedule') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order must be in "Pending Delivery Schedule" status to schedule delivery'
                ]);
            }

            $this->purchaseOrderModel->update($id, [
                'status' => 'scheduled_for_delivery'
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Purchase order scheduled for delivery!'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Inventory Staff receives delivered items from Purchase Order
     */
    public function receiveDelivery($id)
    {
        $user = session()->get('user');
        if (!$user || !in_array($user['role'], ['Inventory Staff', 'Branch Manager'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized - Inventory Staff or Branch Manager access only']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $order = $this->purchaseOrderModel->find($id);
            if (!$order) {
                return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
            }

            // Check if order is ready to be received (arriving or delivered status)
            if (!in_array($order['status'], ['arriving', 'delivered'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order must be in "Arriving" or "Delivered" status to receive items'
                ]);
            }

            // Get branch ID from user
            $branchId = $user['branch_id'] ?? $order['branch_id'];
            if (!$branchId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Branch ID is required']);
            }

            // Verify this order is for the user's branch
            if ($order['branch_id'] && $order['branch_id'] != $branchId) {
                return $this->response->setJSON(['success' => false, 'message' => 'This order is not for your branch']);
            }

            // Get PO items
            $poItems = $this->purchaseOrderItemModel->where('purchase_order_id', $id)->findAll();
            if (empty($poItems)) {
                throw new \Exception('Purchase order has no items');
            }

            // Get received items data from request
            $receivedItems = $this->request->getPost('items') ?? $this->request->getJSON(true)['items'] ?? [];
            
            if (empty($receivedItems)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No items received data provided']);
            }

            $receivedCount = 0;
            $damagedCount = 0;
            $expiredCount = 0;
            $errors = [];

            // Process each item
            foreach ($poItems as $poItem) {
                $itemId = $poItem['item_id'];
                $itemName = $poItem['item_name'];
                $expectedQty = floatval($poItem['quantity']);
                
                // If item_id is null, try to find item by name
                if (!$itemId && $itemName) {
                    $itemRecord = $this->itemModel
                        ->where('LOWER(name)', strtolower($itemName))
                        ->first();
                    if ($itemRecord) {
                        $itemId = $itemRecord['id'];
                    }
                }
                
                // Skip if we still don't have a valid item_id
                if (!$itemId) {
                    $errors[] = "Item '{$itemName}' not found in system. Please add it to inventory first.";
                    continue;
                }
                
                // Find corresponding received item data
                $receivedItem = null;
                foreach ($receivedItems as $ri) {
                    if (($ri['item_id'] ?? null) == $itemId || 
                        (isset($ri['item_name']) && strtolower($ri['item_name']) == strtolower($itemName))) {
                        $receivedItem = $ri;
                        break;
                    }
                }

                if (!$receivedItem) {
                    $errors[] = "No data for item: {$itemName}";
                    continue;
                }

                $receivedQty = floatval($receivedItem['received_quantity'] ?? $expectedQty);
                $damagedQty = floatval($receivedItem['damaged_quantity'] ?? 0);
                $expiredQty = floatval($receivedItem['expired_quantity'] ?? 0);
                $expiryDate = $receivedItem['expiry_date'] ?? null;

                // Validate quantities
                if ($receivedQty + $damagedQty + $expiredQty > $expectedQty) {
                    $errors[] = "Total quantities for {$itemName} exceed expected quantity";
                    continue;
                }

                // Add good items to stock
                if ($receivedQty > 0) {
                    $this->inventoryService->receiveStock(
                        $branchId,
                        $itemId,
                        $receivedQty,
                        $user['id'],
                        "Received from PO: {$order['po_id']}",
                        $expiryDate
                    );
                    $receivedCount++;
                }

                // Record damaged items
                if ($damagedQty > 0) {
                    $this->inventoryService->recordSpoilage(
                        $branchId,
                        $itemId,
                        $damagedQty,
                        $user['id'],
                        "Damaged items from PO: {$order['po_id']} - " . ($receivedItem['damage_notes'] ?? 'No notes')
                    );
                    $damagedCount++;
                }

                // Record expired items
                if ($expiredQty > 0) {
                    $this->inventoryService->recordSpoilage(
                        $branchId,
                        $itemId,
                        $expiredQty,
                        $user['id'],
                        "Expired items from PO: {$order['po_id']} - " . ($receivedItem['expiry_notes'] ?? 'No notes')
                    );
                    $expiredCount++;
                }
            }

            // Update PO status to delivered_to_branch
            $this->purchaseOrderModel->update($id, [
                'status' => 'delivered_to_branch',
                'notes' => ($order['notes'] ?? '') . "\n\n[Received at Branch - " . date('Y-m-d H:i:s') . "]: Items received by " . $user['username']
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            $message = "Items received successfully! ";
            $message .= "Received: {$receivedCount} items. ";
            if ($damagedCount > 0) $message .= "Damaged: {$damagedCount} items. ";
            if ($expiredCount > 0) $message .= "Expired: {$expiredCount} items. ";
            if (!empty($errors)) $message .= "Errors: " . implode(', ', $errors);

            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Branch Manager confirms delivered goods are correct
     */
    public function confirmDelivery($id)
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'Branch Manager') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized - Branch Manager access only']);
        }

        try {
            $order = $this->purchaseOrderModel->find($id);
            if (!$order) {
                return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
            }

            // Check if order is in 'delivered_to_branch' status
            if ($order['status'] !== 'delivered_to_branch') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order must be in "Delivered to Branch" status to confirm'
                ]);
            }

            // Get branch ID from user
            $branchId = $user['branch_id'] ?? $order['branch_id'];
            if (!$branchId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Branch ID is required']);
            }

            // Verify this order is for the user's branch
            if ($order['branch_id'] && $order['branch_id'] != $branchId) {
                return $this->response->setJSON(['success' => false, 'message' => 'This order is not for your branch']);
            }

            // Get confirmation notes (optional)
            $confirmationNotes = $this->request->getPost('confirmation_notes') ?? $this->request->getJSON(true)['confirmation_notes'] ?? null;

            // Update PO status to completed
            $updateData = [
                'status' => 'completed',
                'notes' => ($order['notes'] ?? '') . "\n\n[Confirmed by Branch Manager - " . date('Y-m-d H:i:s') . "]: " . ($confirmationNotes ?: 'Delivery confirmed and stock verified')
            ];

            // Record who confirmed it (we can use approved_by field or add a new field)
            // For now, we'll add it to notes
            $this->purchaseOrderModel->update($id, $updateData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Delivery confirmed! Stock has been officially recorded in branch inventory.'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Supplier orders page - shows orders assigned to the supplier
     */
    public function supplierOrders()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'Supplier') {
            return redirect()->to(base_url('login'));
        }

        $supplierId = $user['supplier_id'] ?? null;
        if (!$supplierId) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Supplier account not properly configured');
        }

        // Get supplier info
        $supplier = $this->supplierModel->find($supplierId);

        $data = [
            'orders' => $this->purchaseOrderModel->getOrdersWithDetails(null, $supplierId),
            'stats' => $this->purchaseOrderModel->getStats(null, $supplierId),
            'supplier' => $supplier,
        ];

        // Filter out orders that are still waiting for scheduling (pending_delivery_schedule)
        // Suppliers should only see orders that have been scheduled or are in progress
        $data['orders'] = array_filter($data['orders'], function($order) {
            return $order['status'] !== 'pending_delivery_schedule';
        });

        // Load items for each order with proper joins to ensure item data
        $db = \Config\Database::connect();
        foreach ($data['orders'] as &$order) {
            $order['items'] = $db->table('purchase_order_items poi')
                ->select('poi.*, i.name as item_name_from_db, i.unit as item_unit_from_db')
                ->join('items i', 'poi.item_id = i.id', 'left')
                ->where('poi.purchase_order_id', $order['id'])
                ->get()
                ->getResultArray();

            // Ensure each item has a name (prefer the stored item_name, fallback to DB name)
            foreach ($order['items'] as &$item) {
                if (empty($item['item_name']) && !empty($item['item_name_from_db'])) {
                    $item['item_name'] = $item['item_name_from_db'];
                }
                if (empty($item['unit']) && !empty($item['item_unit_from_db'])) {
                    $item['unit'] = $item['item_unit_from_db'];
                }
            }
        }

        return view('supplier-orders', $data);
    }

    /**
     * Supplier deliveries page (placeholder)
     */
    public function supplierDeliveries()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'Supplier') {
            return redirect()->to(base_url('login'));
        }

        $supplierId = $user['supplier_id'] ?? null;
        if (!$supplierId) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Supplier account not properly configured');
        }

        // Get supplier info
        $supplier = $this->supplierModel->find($supplierId);

        $data = [
            'supplier' => $supplier,
        ];

        return view('supplier-deliveries', $data);
    }

    /**
     * Supplier invoices page (placeholder)
     */
    public function supplierInvoices()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'Supplier') {
            return redirect()->to(base_url('login'));
        }

        $supplierId = $user['supplier_id'] ?? null;
        if (!$supplierId) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Supplier account not properly configured');
        }

        // Get supplier info
        $supplier = $this->supplierModel->find($supplierId);

        $data = [
            'supplier' => $supplier,
        ];

        return view('supplier-invoices', $data);
    }

    /**
     * Create delivery record when PO status changes to in_transit
     */
    private function createDeliveryFromPO($order, $user)
    {
        try {
            // Load required models
            $deliveryModel = new \App\Models\DeliveryModel();

            // Generate delivery ID
            $deliveryId = $deliveryModel->generateDeliveryId();

            // Get PO items
            $poItems = $this->purchaseOrderItemModel->where('purchase_order_id', $order['id'])->findAll();

            // Create delivery record (main delivery info only)
            $deliveryData = [
                'delivery_id' => $deliveryId,
                'purchase_order_id' => $order['id'],
                'branch_id' => $order['branch_id'],
                'supplier_id' => $order['supplier_id'],
                'driver' => 'Delivery Driver',
                'status' => 'in_transit',
                'scheduled_time' => date('Y-m-d H:i:s'),
                'notes' => "Delivery for PO: {$order['po_id']}",
                'created_by' => $user['id'],
            ];

            $deliveryRecordId = $deliveryModel->insert($deliveryData);

            // Create delivery items (exactly like purchase_order_items)
            foreach ($poItems as $item) {
                $this->deliveryItemModel->insert([
                    'delivery_id' => $deliveryId, // Use delivery_id string, not the record ID
                    'item_id' => $item['item_id'],
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'] ?? null,
                    'unit_price' => $item['unit_price'] ?? 0,
                    'status' => 'in_transit',
                ]);
            }

            // Log the creation
            log_message('info', "Delivery {$deliveryId} created from PO {$order['po_id']}");

        } catch (\Exception $e) {
            // Log error but don't fail the PO status update
            log_message('error', 'Failed to create delivery from PO: ' . $e->getMessage());
        }
    }

    /**
     * Update delivery status when PO status changes
     */
    private function updateDeliveryStatus($purchaseOrderId, $newStatus)
    {
        try {
            // Find delivery record linked to this PO
            $delivery = $this->deliveryModel->where('purchase_order_id', $purchaseOrderId)->first();
            
            if ($delivery) {
                // Map PO status to delivery status
                $deliveryStatus = $this->mapPOStatusToDeliveryStatus($newStatus);
                
                if ($deliveryStatus) {
                    $this->deliveryModel->update($delivery['id'], [
                        'status' => $deliveryStatus,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    // Also update delivery items status
                    $this->deliveryItemModel->where('delivery_id', $delivery['delivery_id'])
                        ->set(['status' => $deliveryStatus, 'updated_at' => date('Y-m-d H:i:s')])
                        ->update();
                    
                    log_message('info', "Updated delivery {$delivery['delivery_id']} status to {$deliveryStatus}");
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to update delivery status: ' . $e->getMessage());
        }
    }

    /**
     * Map Purchase Order status to Delivery status
     */
    private function mapPOStatusToDeliveryStatus($poStatus)
    {
        $statusMap = [
            'in_transit' => 'in_transit',
            'delayed' => 'delayed',
            'arrived' => 'arrived', // Arrived at destination, ready to claim
            'delivered' => 'delivered', // After claimed and invoice sent
            'completed' => 'received'
        ];
        
        return $statusMap[$poStatus] ?? null;
    }

    /**
     * Get purchase order details
     */
    public function get($id)
    {
        $order = $this->purchaseOrderModel->getOrderWithItems($id);
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        return $this->response->setJSON(['success' => true, 'data' => $order]);
    }


}
