<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BranchModel;
use App\Models\SupplierModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $branchModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->branchModel = new BranchModel();
        $this->supplierModel = new SupplierModel();
    }

    /**
     * Check if user is System Administrator
     */
    private function checkAdminAccess()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'System Administrator') {
            return redirect()->to(base_url('dashboard'))->with('error', 'Access denied. System Administrator access required.');
        }
        return null;
    }

    /**
     * List all users
     */
    public function index()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        $currentUser = session()->get('user');

        $data = [
            'users' => $this->userModel->getUsersWithDetails(),
            'branches' => $this->branchModel->findAll(),
            'suppliers' => $this->supplierModel->findAll(),
            'title' => 'User Management',
            'currentUserId' => $currentUser['id']
        ];

        return view('users/index', $data);
    }

    /**
     * Show create user form
     */
    public function create()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        $data = [
            'branches' => $this->branchModel->findAll(),
            'suppliers' => $this->supplierModel->findAll(),
            'title' => 'Create User'
        ];

        return view('users/create', $data);
    }

    /**
     * Store new user
     */
    public function store()
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[System Administrator,Central Office Admin,Branch Manager,Inventory Staff,Supplier,Logistics Coordinator,Franchise Manager]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'supplier_id' => $this->request->getPost('supplier_id') ?: null,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to(base_url('users'))->with('success', 'User created successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create user');
        }
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        // Validate ID
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->to(base_url('users'))->with('error', 'Invalid user ID');
        }

        // Get user data using direct database query to ensure all fields are retrieved
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) {
            return redirect()->to(base_url('users'))->with('error', 'User not found');
        }

        // Prevent editing inactive users
        if ($user['status'] === 'inactive') {
            return redirect()->to(base_url('users'))->with('error', 'Cannot edit inactive users. Please activate the user first.');
        }



        // Get branches and suppliers for dropdowns
        $branches = $this->branchModel->findAll();
        $suppliers = $this->supplierModel->findAll();

        $data = [
            'user' => $user,
            'branches' => $branches,
            'suppliers' => $suppliers,
            'title' => 'Edit User'
        ];

        return view('users/edit', $data);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        // Get user data using direct database query
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) {
            return redirect()->to(base_url('users'))->with('error', 'User not found');
        }

        // Prevent updating inactive users
        if ($user['status'] === 'inactive') {
            return redirect()->to(base_url('users'))->with('error', 'Cannot update inactive users. Please activate the user first.');
        }

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,' . $id . ']',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role' => 'required|in_list[System Administrator,Central Office Admin,Branch Manager,Inventory Staff,Supplier,Logistics Coordinator,Franchise Manager]',
        ];

        // Add password validation only if password is provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'supplier_id' => $this->request->getPost('supplier_id') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Update password only if provided
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to(base_url('users'))->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update user');
        }
    }

    /**
     * Deactivate user
     */
    public function deactivate($id)
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('users'))->with('error', 'User not found');
        }

        // Prevent deactivating self
        $currentUser = session()->get('user');
        if ($currentUser['id'] == $id) {
            return redirect()->to(base_url('users'))->with('error', 'Cannot deactivate your own account');
        }

        if ($this->userModel->update($id, ['status' => 'inactive', 'updated_at' => date('Y-m-d H:i:s')])) {
            return redirect()->to(base_url('users'))->with('success', 'User deactivated successfully');
        } else {
            return redirect()->to(base_url('users'))->with('error', 'Failed to deactivate user');
        }
    }

    /**
     * Activate user
     */
    public function activate($id)
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        if ($this->userModel->update($id, ['status' => 'active', 'updated_at' => date('Y-m-d H:i:s')])) {
            return redirect()->to(base_url('users'))->with('success', 'User activated successfully');
        } else {
            return redirect()->to(base_url('users'))->with('error', 'Failed to activate user');
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword($id)
    {
        $redirect = $this->checkAdminAccess();
        if ($redirect) return $redirect;

        $newPassword = $this->request->getPost('new_password');
        if (!$newPassword || strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password must be at least 6 characters');
        }

        if ($this->userModel->update($id, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ])) {
            return redirect()->to(base_url('users'))->with('success', 'Password reset successfully');
        } else {
            return redirect()->to(base_url('users'))->with('error', 'Failed to reset password');
        }
    }
}
