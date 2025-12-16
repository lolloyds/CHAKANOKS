<?php

namespace App\Controllers;
use App\Models\SupplierModel;

class Suppliers extends BaseController
{
    public function index()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $model = new SupplierModel();

        // If user is a supplier, show their profile instead of full list
        if ($user['role'] === 'Supplier' && isset($user['supplier_id'])) {
            $supplier = $model->find($user['supplier_id']);
            if (!$supplier) {
                return redirect()->to(base_url('dashboard'))->with('error', 'Supplier profile not found');
            }

            // Get supplier-specific stats
            $db = \Config\Database::connect();
            $supplierStats = [
                'total_orders' => $db->table('purchase_orders')
                    ->where('supplier_id', $user['supplier_id'])
                    ->countAllResults(),
                'pending_orders' => $db->table('purchase_orders')
                    ->where('supplier_id', $user['supplier_id'])
                    ->whereIn('status', ['pending', 'po_issued_to_supplier'])
                    ->countAllResults(),
                'active_orders' => $db->table('purchase_orders')
                    ->where('supplier_id', $user['supplier_id'])
                    ->whereIn('status', ['scheduled_for_delivery', 'in_transit', 'arriving'])
                    ->countAllResults(),
                'completed_orders' => $db->table('purchase_orders')
                    ->where('supplier_id', $user['supplier_id'])
                    ->where('status', 'completed')
                    ->countAllResults(),
                'supplier_info' => $supplier
            ];

            // Get recent orders for this supplier
            $recentOrders = $db->table('purchase_orders po')
                ->select('po.*, b.name as branch_name, pr.request_id')
                ->join('branches b', 'po.branch_id = b.id', 'left')
                ->join('purchase_requests pr', 'po.purchase_request_id = pr.id', 'left')
                ->where('po.supplier_id', $user['supplier_id'])
                ->orderBy('po.created_at', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();

            $data = [
                'isSupplierView' => true,
                'supplier' => $supplier,
                'stats' => $supplierStats,
                'recentOrders' => $recentOrders,
                'userRole' => $user['role'],
            ];

        } else {
            // Admin/central office view - show full supplier list
            $data['stats'] = $model->getStats();

            // Get all suppliers
            $suppliers = $model->orderBy('id', 'DESC')->findAll();

            $data['suppliers'] = $suppliers;
            $data['userRole'] = $user['role'];
            $data['isSupplierView'] = false;

            // Debug logging
            log_message('debug', 'Admin supplier view - User: ' . $user['role']);
            log_message('debug', 'Suppliers count with direct query: ' . count($suppliers));
            if (count($suppliers) > 0) {
                log_message('debug', 'First supplier with direct: ' . json_encode($suppliers[0]));
            }
        }

        return view('suppliers', $data);
    }

    public function create()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $model = new SupplierModel();
        $data = [
            'supplier_name' => $this->request->getPost('supplier_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
            'status' => $this->request->getPost('status') ?: 'Active'
        ];

        if ($model->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Supplier created successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to create supplier']);
        }
    }

    public function edit($id)
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $model = new SupplierModel();
        $data['supplier'] = $model->find($id);
        
        if (!$data['supplier']) {
            return redirect()->to('/suppliers')->with('error', 'Supplier not found');
        }
        
        return view('edit_supplier', $data);
    }

    public function update($id)
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $model = new SupplierModel();
        $data = [
            'supplier_name' => $this->request->getPost('supplier_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
            'status' => $this->request->getPost('status')
        ];
        
        if ($this->request->isAJAX()) {
            if ($model->update($id, $data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Supplier updated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update supplier']);
            }
        } else {
            $model->update($id, $data);
            return redirect()->to('/suppliers')->with('success', 'Supplier updated successfully');
        }
    }

    public function delete($id)
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $model = new SupplierModel();
        
        // Set status to Inactive instead of deleting
        if ($this->request->isAJAX()) {
            if ($model->update($id, ['status' => 'Inactive'])) {
                return $this->response->setJSON(['success' => true, 'message' => 'Supplier deactivated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to deactivate supplier']);
            }
        } else {
            $model->update($id, ['status' => 'Inactive']);
            return redirect()->to('/suppliers')->with('success', 'Supplier deactivated successfully');
        }
    }
    
    public function restore($id)
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $model = new SupplierModel();
        
        // Restore by setting status to Active
        if ($this->request->isAJAX()) {
            if ($model->update($id, ['status' => 'Active'])) {
                return $this->response->setJSON(['success' => true, 'message' => 'Supplier activated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to activate supplier']);
            }
        } else {
            $model->update($id, ['status' => 'Active']);
            return redirect()->to('/suppliers')->with('success', 'Supplier activated successfully');
        }
    }
}
