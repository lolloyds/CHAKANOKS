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

            try {
                $userModel = new UserModel();
                $user = $userModel->where('username', $username)->first();

                if (!$user) {
                    log_message('error', "Login attempt failed: User '$username' not found");
                    session()->setFlashdata('error', 'Invalid username or password.');
                    return redirect()->to(base_url('login'));
                }

                if (!password_verify($password, $user['password_hash'])) {
                    log_message('error', "Login attempt failed: Invalid password for user '$username'");
                    session()->setFlashdata('error', 'Invalid username or password.');
                    return redirect()->to(base_url('login'));
                }

                log_message('info', "User '$username' logged in successfully");

                session()->set('user', [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'branch_id' => $user['branch_id'] ?? null,
                    'supplier_id' => $user['supplier_id'] ?? null,
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
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Database connection error. Please check your database configuration.');
                log_message('error', 'Login error: ' . $e->getMessage());
                return redirect()->to(base_url('login'));
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
            // Central office user - get dashboard stats
            $db = \Config\Database::connect();
            
            // Get total branches
            $totalBranches = $db->table('branches')->countAllResults(false);
            
            // Get active deliveries (in_transit or scheduled)
            $activeDeliveries = $db->table('deliveries')
                ->whereIn('status', ['scheduled', 'in_transit'])
                ->countAllResults(false);
            
            // Get pending purchase orders
            $pendingOrders = $db->table('purchase_orders')
                ->whereIn('status', ['pending', 'approved'])
                ->countAllResults(false);
            
            // Get total suppliers (including deleted)
            $totalSuppliers = $db->table('suppliers')->countAllResults(false);
            
            // Get deleted suppliers count
            $deletedSuppliers = $db->table('suppliers')
                ->where('deleted_at IS NOT NULL', null, false)
                ->countAllResults(false);
            
            // Get today's highlights
            $today = date('Y-m-d');
            $scheduledDeliveries = $db->table('deliveries')
                ->where('status', 'scheduled')
                ->where('DATE(scheduled_time)', $today)
                ->countAllResults(false);
            
            $pendingPRs = $db->table('purchase_requests')
                ->whereIn('status', ['pending', 'pending central office review'])
                ->countAllResults(false);
            
            // Get low stock items
            $lowStockQuery = $db->table('branch_stock bs')
                ->select('bs.*, i.reorder_level')
                ->join('items i', 'bs.item_id = i.id')
                ->where('bs.quantity >', 0)
                ->get()
                ->getResultArray();
            
            $lowStockItems = 0;
            foreach ($lowStockQuery as $item) {
                if (($item['quantity'] ?? 0) <= ($item['reorder_level'] ?? 0)) {
                    $lowStockItems++;
                }
            }
            
            // Get recent activity (from stock_movements)
            $recentActivity = $db->table('stock_movements sm')
                ->select('sm.*, i.name as item_name, b.name as branch_name, u.username')
                ->join('items i', 'sm.item_id = i.id', 'left')
                ->join('branches b', 'sm.branch_id = b.id', 'left')
                ->join('users u', 'sm.created_by = u.id', 'left')
                ->orderBy('sm.created_at', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();
            
            $data = [
                'isBranchUser' => false,
                'totalBranches' => $totalBranches,
                'activeDeliveries' => $activeDeliveries,
                'pendingOrders' => $pendingOrders,
                'totalSuppliers' => $totalSuppliers,
                'deletedSuppliers' => $deletedSuppliers,
                'scheduledDeliveries' => $scheduledDeliveries,
                'pendingPRs' => $pendingPRs,
                'lowStockItems' => $lowStockItems,
                'recentActivity' => $recentActivity
            ];
        }

        return view('dashboard', $data);
    }

    public function logout()
    {
        session()->remove('user');
        return redirect()->to(base_url('login'));
    }
}
