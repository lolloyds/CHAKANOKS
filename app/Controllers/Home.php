<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('login');
    }
    public function login(): string
    {
        return view('login');
    }
    public function dashboard(): string
    {
        return view('dashboard');
    }
    public function admin(): string
    {
        return view('admin_dashboard');
    }
    public function user(): string
    {
        return view('user_dashboard');
    }
    public function manager(): string
    {
        return view('branch_manager');
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
    public function transfer(): string
    {
        return view('transfer');
    }
    public function franchise(): string
    {
        return view('franchise');
    }
    public function settings(): string
    {
        return view('settings');
    }
    public function logout()
    {
        return redirect()->to(base_url('login'));
    }
    
    
}
