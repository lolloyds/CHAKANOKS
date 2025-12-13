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

        $branchId = $this->request->getPost('branch_id');
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
            $status = ($user['role'] === 'Branch Manager') ? 'pending central office review' : 'pending';
            
            $requestData = [
                'request_id' => $requestId,
                'branch_id' => $branchId,
                'date_needed' => $this->request->getPost('date_needed'),
                'status' => $status,
                'notes' => $this->request->getPost('notes'),
                'requested_by' => $user['id'],
            ];

            $purchaseRequestId = $this->purchaseRequestModel->insert($requestData);

            // Add items
            $items = $this->request->getPost('items');
            if (is_array($items)) {
                foreach ($items as $item) {
                    if (!empty($item['item_name']) && !empty($item['quantity'])) {
                        // Try to find item by name
                        $itemRecord = $this->itemModel
                            ->where('LOWER(name)', strtolower($item['item_name']))
                            ->first();

                        $this->purchaseRequestItemModel->insert([
                            'purchase_request_id' => $purchaseRequestId,
                            'item_id' => $itemRecord ? $itemRecord['id'] : null,
                            'item_name' => $item['item_name'],
                            'quantity' => $item['quantity'],
                            'unit' => $item['unit'] ?? ($itemRecord ? $itemRecord['unit'] : 'pcs'),
                            'notes' => $item['notes'] ?? null,
                        ]);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Purchase request created successfully!',
                'request_id' => $requestId
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
     */
    public function approve($id)
    {
        $user = session()->get('user');
        if (!$user || !in_array($user['role'], ['Central Office Admin', 'System Administrator'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $request = $this->purchaseRequestModel->find($id);
            if (!$request) {
                return $this->response->setJSON(['success' => false, 'message' => 'Request not found']);
            }

            if (!in_array($request['status'], ['pending', 'pending central office review'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Request is not pending review']);
            }

            // Get supplier_id from request (required for creating PO)
            $supplierId = $this->request->getPost('supplier_id') ?? $this->request->getJSON(true)['supplier_id'] ?? null;
            if (!$supplierId) {
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
                'expected_delivery_date' => $request['date_needed'] ?? null,
                'status' => 'po_issued_to_supplier',
                'notes' => 'Auto-created from approved Purchase Request: ' . $request['request_id'],
                'created_by' => $user['id'],
            ];

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

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Purchase request approved and Purchase Order created!',
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
     */
    public function reject($id)
    {
        $user = session()->get('user');
        if (!$user || !in_array($user['role'], ['Central Office Admin', 'System Administrator'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $request = $this->purchaseRequestModel->find($id);
            if (!$request) {
                return $this->response->setJSON(['success' => false, 'message' => 'Request not found']);
            }

            if (!in_array($request['status'], ['pending', 'pending central office review'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Request is not pending review']);
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




