<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('auth/login');
    }
    public function login(): string
    {
        return view('auth/login');
    }
    public function dashboard(): string
    {
        return view('dashboard');
    }
    public function purchaseRequest(): string
    {
        return view('purchase-request');
    }
    public function purchaseOrders(): string
    {
        return view('purchase-orders');
    }
    public function deliveries(): string
    {
        return view('deliveries');
    }
    public function inventory(): string
    {
        return view('inventory');
    }

    public function suppliers(): string
    {
        return view('suppliers');
    }
    public function transfer()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $transferModel = new \App\Models\TransferModel();
        $branchId = $user['branch_id'] ?? null;
        
        $data = [
            'transfers' => $transferModel->getTransfersWithDetails($branchId),
            'stats' => $transferModel->getStats($branchId)
        ];

        return view('transfer', $data);
    }
    public function franchise()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $franchiseModel = new \App\Models\FranchiseModel();
        
        $data = [
            'franchises' => $franchiseModel->orderBy('created_at', 'DESC')->findAll(),
            'stats' => $franchiseModel->getStats(),
        ];

        return view('franchise', $data);
    }
    public function settings()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }
        return view('settings');
    }

    public function updateProfile()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');

        // Store in session for now (can be extended to save in database)
        $userData = session()->get('user');
        if ($name) $userData['name'] = $name;
        if ($email) $userData['email'] = $email;
        if ($phone) $userData['phone'] = $phone;
        session()->set('user', $userData);

        return $this->response->setJSON(['success' => true, 'message' => 'Profile updated successfully!']);
    }

    public function updatePassword()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON(['success' => false, 'message' => 'New password and confirm password do not match!']);
        }

        if (strlen($newPassword) < 6) {
            return $this->response->setJSON(['success' => false, 'message' => 'Password must be at least 6 characters long!']);
        }

        // Verify current password
        $userModel = new \App\Models\UserModel();
        $userData = $userModel->find($user['id']);

        if (!$userData || !password_verify($currentPassword, $userData['password_hash'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Current password is incorrect!']);
        }

        // Update password
        $userModel->update($user['id'], [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Password updated successfully!']);
    }

    public function updateNotifications()
    {
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $emailNotifications = $this->request->getPost('email_notifications');
        $smsNotifications = $this->request->getPost('sms_notifications');
        $theme = $this->request->getPost('theme');

        // Store in session for now (can be extended to save in database)
        $userData = session()->get('user');
        $userData['email_notifications'] = $emailNotifications;
        $userData['sms_notifications'] = $smsNotifications;
        $userData['theme'] = $theme;
        session()->set('user', $userData);

        return $this->response->setJSON(['success' => true, 'message' => 'Preferences saved successfully!']);
    }
    public function logout()
    {
        return redirect()->to(base_url('login'));
    }
    public function deliveriesSummary()
    {
        $deliveryModel = new \App\Models\DeliveryModel();
        $deliveries = $deliveryModel->findAll();
        return view('dashboard/deliveries_summary', ['deliveries' => $deliveries]);
    }
}
