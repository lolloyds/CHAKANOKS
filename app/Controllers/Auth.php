<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // Show the login view
        return view('login');
    }

    public function doLogin()
    {
        // Get posted inputs
        $usernameOrEmail = $this->request->getPost('username_email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember'); // checkbox

        $userModel = new UserModel();

        // Allow login using either username OR email
        $user = $userModel
            ->groupStart()
                ->where('username', $usernameOrEmail)
                ->orWhere('email', $usernameOrEmail)
            ->groupEnd()
            ->first();

        // Validate user
        if (!$user || !password_ve_
