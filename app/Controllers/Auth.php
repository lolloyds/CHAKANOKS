<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('login');
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
        ]);

        switch ($user['role']) {
            case 'admin':
                return redirect()->to(base_url('admin'));
            case 'inventory_staff':
                return redirect()->to(base_url('inventory-staff'));
            case 'branch_manager':
                return redirect()->to(base_url('manager'));
            case 'user':
            default:
                return redirect()->to(base_url('user'));
        }
    }

    public function logout()
    {
        session()->remove('user');
        return redirect()->to(base_url('login'));
    }
}


