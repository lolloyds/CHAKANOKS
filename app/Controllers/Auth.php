<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
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
                return redirect()->to(base_url('bdashboard'));
            case 'Branch Manager':
                return redirect()->to(base_url('bdashboard'));
            case 'Central Office Admin':
            case 'Supplier':
            case 'Logistics Coordinator':
            case 'Franchise Manager':
            case 'System Administrator':
            default:
                return redirect()->to(base_url('dashboard'));
        }
    }

    public function logout()
    {
        session()->remove('user');
        return redirect()->to(base_url('login'));
    }
}
