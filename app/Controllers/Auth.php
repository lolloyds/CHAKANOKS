<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $userModel = new UserModel();
            $user = $userModel->where('username', $username)->first();

            if (!$user || !password_verify($password, $user['password_hash'])) {
                return redirect()->to(base_url('login'));
            }

            session()->set('user', [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'branch_id' => $user['branch_id'] ?? null,
            ]);

            switch ($user['role']) {
                case 'Inventory Staff':
                case 'Branch Manager':
                case 'Central Office Admin':
                case 'Supplier':
                case 'Logistics Coordinator':
                case 'Franchise Manager':
                case 'System Administrator':
                default:
                    return redirect()->to(base_url('dashboard'));
            }
        }

        return view('auth/login');
    }

    public function dashboard()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $data = [];

        // If branch user, get branch-specific data
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
            // Central office user
            $data['isBranchUser'] = false;
        }

        return view('dashboard', $data);
    }

    public function logout()
    {
        session()->remove('user');
        return redirect()->to(base_url('login'));
    }
}
