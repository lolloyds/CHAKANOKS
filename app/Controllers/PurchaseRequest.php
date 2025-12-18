<?php

namespace App\Controllers;

use App\Models\PurchaseRequestModel;
use App\Models\PurchaseRequestItemModel;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\ItemModel;
use App\Models\BranchModel;
use App\Models\SupplierModel;

class PurchaseRequest extends BaseController
{
    protected $purchaseRequestModel;
    protected $purchaseRequestItemModel;
    protected $purchaseOrderModel;
    protected $purchaseOrderItemModel;
    protected $itemModel;
    protected $branchModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseRequestItemModel = new PurchaseRequestItemModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->purchaseOrderItemModel = new PurchaseOrderItemModel();
        $this->itemModel = new ItemModel();
        $this->branchModel = new \App\Models\BranchModel();
        $this->supplierModel = new SupplierModel();
    }

    /**
     * Display purchase requests page
     */
    public function index()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $branchId = null;
        if (in_array($user['role'], ['Inventory Staff', 'Branch Manager']) && isset($user['branch_id'])) {
            $branchId = $user['branch_id'];
        }

        $data = [
            'requests' => $this->purchaseRequestModel->getRequestsWithDetails($branchId),
            'stats' => $this->purchaseRequestModel->getStats($branchId),
            'branches' => $this->branchModel->findAll(),
            'items' => $this->itemModel->where('status', 'active')->findAll(),
            'suppliers' => $this->supplierModel->where('status', 'Active')->findAll(),
            'userRole' => $user['role'],
            'branchId' => $branchId,
        ];

        // Load items for each request
        foreach ($data['requests'] as &$request) {
            $request['items'] = $this->purchaseRequestItemModel
                ->where('purchase_request_id', $request['id'])
                ->findAll();
        }

        return view('purchase-request', $data);
    }

    /**
     * Create new purchase request
     */
    public function create()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // Get JSON data instead of POST data
        $jsonData = $this->request->getJSON(true);
        
        $branchId = $jsonData['branch_id'] ?? null;
        if (in_array($user['role'], ['Inventory Staff', 'Branch Manager']) && isset($user['branch_id'])) {
            $branchId = $user['branch_id']; // Branch users can only create for their branch
        }

        if (!$branchId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Branch is required']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Create purchase request
            $requestId = $this->purchaseRequestModel->generateRequestId();
            
            // Set status based on user role: Branch Manager creates PR with "pending central office review" status
            $status = 'pending_central_office_review';
            
            $requestData = [
                'pr_id' => $requestId,
                'branch_id' => $branchId,
                'request_date' => date('Y-m-d'),
                'needed_by_date' => $jsonData['needed_by_date'] ?? null,
                'status' => $status,
                'priority' => $jsonData['priority'] ?? 'medium',
                'notes' => $jsonData['notes'] ?? null,
                'created_by' => $user['id'],
            ];

            $purchaseRequestId = $this->purchaseRequestModel->insert($requestData);

            // Add items
            $items = $jsonData['items'] ?? [];

            // Debug logging
            log_message('debug', 'JSON Data received: ' . json_encode($jsonData));
            log_message('debug', 'Items array: ' . json_encode($items));
            log_message('debug', 'Items count: ' . count($items));

            if (is_array($items) && count($items) > 0) {
                foreach ($items as $index => $item) {
                    log_message('debug', "Processing item $index: " . json_encode($item));
                    log_message('debug', "Item name value: '" . ($item['item_name'] ?? 'NULL') . "'");
                    log_message('debug', "Item quantity value: '" . ($item['quantity'] ?? 'NULL') . "'");

                    // Ensure item_name and quantity are properly trimmed and validated
                    $itemName = trim($item['item_name'] ?? '');
                    $itemQuantity = $item['quantity'] ?? null;

                    log_message('debug', "Trimmed item name: '$itemName', quantity: '$itemQuantity'");

                    if (!empty($itemName) && !empty($itemQuantity) && is_numeric($itemQuantity)) {
                        try {
                            // Try to find item by name
                            $itemRecord = $this->itemModel
                                ->where('LOWER(name)', strtolower($itemName))
                                ->first();

                            $itemData = [
                                'purchase_request_id' => $purchaseRequestId,
                                'item_id' => $itemRecord ? $itemRecord['id'] : null,
                                'item_name' => $itemName, // Use trimmed name
                                'quantity' => floatval($itemQuantity), // Ensure numeric conversion
                                'unit' => trim($item['unit'] ?? ($itemRecord ? $itemRecord['unit'] : 'pcs')),
                                'notes' => trim($item['notes'] ?? ''),
                            ];

                            log_message('debug', 'Inserting item data: ' . json_encode($itemData));

                            $itemInsertResult = $this->purchaseRequestItemModel->insert($itemData);
                            log_message('debug', 'Item insert result: ' . ($itemInsertResult ? 'SUCCESS with ID: ' . $itemInsertResult : 'FAILED'));

                            if (!$itemInsertResult) {
                                $errors = $this->purchaseRequestItemModel->errors();
                                log_message('error', 'Failed to insert item: ' . json_encode($errors));

                                // Revert the transaction on any item insert failure
                                $db->transRollback();
                                return $this->response->setJSON([
                                    'success' => false,
                                    'message' => 'Failed to save item: ' . implode(', ', $errors)
                                ]);
                            }
                        } catch (\Exception $e) {
                            log_message('error', 'Exception while inserting item: ' . $e->getMessage());
                            $db->transRollback();
                            return $this->response->setJSON([
                                'success' => false,
                                'message' => 'Error saving items: ' . $e->getMessage()
                            ]);
                        }
                    } else {
                        log_message('debug', "Skipping item $index - name empty or invalid: '$itemName', quantity: '$itemQuantity', is_numeric: " . (is_numeric($itemQuantity) ? 'TRUE' : 'FALSE'));
                    }
                }
            } else {
                log_message('debug', 'No items to process or items is not an array');
                // If no items, the PR should still be created successfully
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Purchase request created successfully!',
                'pr_id' => $requestId
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
     * Approve purchase request and create Purchase Order
     * Branch Managers can approve PRs from their branch and auto-create POs
     * Central Office Admins can approve any PR and create POs
     */
    public function approve($id)
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $request = $this->purchaseRequestModel->find($id);
            if (!$request) {
                return $this->response->setJSON(['success' => false, 'message' => 'Request not found']);
            }

            if (!in_array($request['status'], ['pending_central_office_review'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Request is not pending review']);
            }

            // Authorization check: Branch Managers can only approve PRs from their branch
            $isAuthorized = false;
            if (in_array($user['role'], ['Central Office Admin', 'System Administrator'])) {
                $isAuthorized = true; // Central office can approve any PR
            } elseif ($user['role'] === 'Branch Manager' && isset($user['branch_id']) && $request['branch_id'] == $user['branch_id']) {
                $isAuthorized = true; // Branch Manager can only approve PRs from their branch
            }

            if (!$isAuthorized) {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized to approve this request']);
            }

            // Get supplier_id from request or auto-determine for Branch Managers
            $jsonData = $this->request->getJSON(true);
            $supplierId = $this->request->getPost('supplier_id') ?? $jsonData['supplier_id'] ?? null;

            // For Branch Managers and Central Office Admin, automatically determine supplier based on items
            if (in_array($user['role'], ['Branch Manager', 'Central Office Admin']) && !$supplierId) {
                // Get PR items to determine supplier
                $prItems = $this->purchaseRequestItemModel->where('purchase_request_id', $id)->findAll();
                if (empty($prItems)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Purchase request has no items']);
                }

                // Load SupplierProductModel to find suppliers for items
                $supplierProductModel = new \App\Models\SupplierProductModel();
                $itemNames = array_column($prItems, 'item_name');
                $supplierItems = $supplierProductModel->getSuppliersForItems($itemNames);

                if (empty($supplierItems)) {
                    return $this->response->setJSON(['success' => false, 'message' => 'No suppliers found for the requested items']);
                }

                // Check if all items can be supplied by a single supplier
                $supplierCounts = [];
                foreach ($supplierItems as $supplierItem) {
                    $supplierCounts[$supplierItem['supplier_id']] = ($supplierCounts[$supplierItem['supplier_id']] ?? 0) + 1;
                }

                // Find supplier that can provide the most items
                $bestSupplierId = array_keys($supplierCounts, max($supplierCounts))[0];
                $supplierId = $bestSupplierId;

                log_message('debug', 'Branch Manager auto-selected supplier: ' . $supplierId);
            }

            log_message('debug', 'Approve request - JSON data received: ' . json_encode($jsonData));
            log_message('debug', 'Approve request - POST data: ' . json_encode($this->request->getPost()));
            log_message('debug', 'Approve request - Supplier ID: ' . $supplierId);

            // Validate that supplier exists and is active
            if ($supplierId) {
                $supplier = $this->supplierModel->where('id', $supplierId)->where('status', 'Active')->first();
                if (!$supplier) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Selected supplier not found or inactive']);
                }
                log_message('debug', 'Approve request - Supplier validated: ' . $supplier['supplier_name']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Supplier is required to create Purchase Order']);
            }

            // Update PR status to approved
            $this->purchaseRequestModel->update($id, [
                'status' => 'approved',
                'approved_by' => $user['id'],
                'approved_at' => date('Y-m-d H:i:s'),
            ]);

            // Get PR items
            $prItems = $this->purchaseRequestItemModel->where('purchase_request_id', $id)->findAll();
            if (empty($prItems)) {
                throw new \Exception('Purchase request has no items');
            }

            // Create Purchase Order
            $poId = $this->purchaseOrderModel->generatePoId();
            $orderData = [
                'po_id' => $poId,
                'purchase_request_id' => $id,
                'supplier_id' => $supplierId,
                'branch_id' => $request['branch_id'],
                'order_date' => date('Y-m-d'),
                'expected_delivery_date' => $request['needed_by_date'] ?? null,
            ];

            // Set initial status based on user role
            if ($user['role'] === 'Branch Manager') {
                $orderData['status'] = 'pending'; // Branch-approved POs need central review
                $orderData['notes'] = 'Branch-approved PR auto-converted to PO: ' . $request['pr_id'] . ' - Pending Central Office review';
            } else {
                $orderData['status'] = 'pending_delivery_schedule'; // Central office approval goes direct to supplier
                $orderData['notes'] = 'Central Office approved PR converted to PO: ' . $request['pr_id'];
            }

            $orderData['created_by'] = $user['id'];
            $purchaseOrderId = $this->purchaseOrderModel->insert($orderData);

            // Copy items from PR to PO
            $totalCost = 0;
            foreach ($prItems as $prItem) {
                // Try to find item by name or ID
                $itemRecord = null;
                if ($prItem['item_id']) {
                    $itemRecord = $this->itemModel->find($prItem['item_id']);
                }
                if (!$itemRecord && $prItem['item_name']) {
                    $itemRecord = $this->itemModel
                        ->where('LOWER(name)', strtolower($prItem['item_name']))
                        ->first();
                }

                // For PO items, we need unit_price - set to 0 initially, can be updated later
                $unitPrice = 0;
                $quantity = floatval($prItem['quantity']);
                $totalPrice = $unitPrice * $quantity;
                $totalCost += $totalPrice;

                $this->purchaseOrderItemModel->insert([
                    'purchase_order_id' => $purchaseOrderId,
                    'item_id' => $itemRecord ? $itemRecord['id'] : $prItem['item_id'],
                    'item_name' => $prItem['item_name'],
                    'quantity' => $quantity,
                    'unit' => $prItem['unit'] ?? ($itemRecord ? $itemRecord['unit'] : 'pcs'),
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'notes' => $prItem['notes'] ?? null,
                ]);
            }

            // Update PO total cost
            $this->purchaseOrderModel->update($purchaseOrderId, ['total_cost' => $totalCost]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            $approverType = $user['role'] === 'Branch Manager' ? 'Branch Manager' : 'Central Office';
            $statusMessage = $user['role'] === 'Branch Manager' ?
                'Purchase request approved and Purchase Order created (pending Central Office review)!' :
                'Purchase request approved and Purchase Order created!';

            return $this->response->setJSON([
                'success' => true,
                'message' => $statusMessage,
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
     * Reject purchase request
     * Branch Managers can reject PRs from their branch
     * Central Office Admins can reject any PR
     */
    public function reject($id)
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $request = $this->purchaseRequestModel->find($id);
            if (!$request) {
                return $this->response->setJSON(['success' => false, 'message' => 'Request not found']);
            }

            if (!in_array($request['status'], ['pending_central_office_review'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Request is not pending review']);
            }

            // Authorization check: Branch Managers can only reject PRs from their branch
            $isAuthorized = false;
            if (in_array($user['role'], ['Central Office Admin', 'System Administrator'])) {
                $isAuthorized = true; // Central office can reject any PR
            } elseif ($user['role'] === 'Branch Manager' && isset($user['branch_id']) && $request['branch_id'] == $user['branch_id']) {
                $isAuthorized = true; // Branch Manager can only reject PRs from their branch
            }

            if (!$isAuthorized) {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized to reject this request']);
            }

            // Get rejection reason from POST or JSON
            $rejectionReason = $this->request->getPost('rejection_reason');
            if (!$rejectionReason) {
                $jsonData = $this->request->getJSON(true);
                $rejectionReason = $jsonData['rejection_reason'] ?? null;
            }

            $this->purchaseRequestModel->update($id, [
                'status' => 'rejected',
                'approved_by' => $user['id'],
                'approved_at' => date('Y-m-d H:i:s'),
                'rejection_reason' => $rejectionReason,
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Purchase request rejected!'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get purchase request details
     */
    public function get($id)
    {
        $request = $this->purchaseRequestModel->getRequestWithItems($id);
        if (!$request) {
            return $this->response->setJSON(['success' => false, 'message' => 'Request not found']);
        }

        return $this->response->setJSON(['success' => true, 'data' => $request]);
    }
}
