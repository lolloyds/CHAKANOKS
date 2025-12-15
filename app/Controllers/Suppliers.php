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

        $data['stats'] = $model->getStats();
        // Get all suppliers including deleted ones
        $data['suppliers'] = $model->getAllWithDeleted();
        $data['userRole'] = $user['role'];

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
            'supply_type' => $this->request->getPost('supply_type'),
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
        $data['supplier'] = $model->withDeleted()->find($id);
        
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
            'supply_type' => $this->request->getPost('supply_type'),
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
        
        // Soft delete - just set deleted_at
        if ($this->request->isAJAX()) {
            if ($model->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Supplier deleted successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete supplier']);
            }
        } else {
            $model->delete($id);
            return redirect()->to('/suppliers')->with('success', 'Supplier deleted successfully');
        }
    }
    
    public function restore($id)
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $model = new SupplierModel();
        $db = \Config\Database::connect();
        
        // Restore by setting deleted_at to null
        $db->table('suppliers')->where('id', $id)->update(['deleted_at' => null]);
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Supplier restored successfully']);
        } else {
            return redirect()->to('/suppliers')->with('success', 'Supplier restored successfully');
        }
    }
}
