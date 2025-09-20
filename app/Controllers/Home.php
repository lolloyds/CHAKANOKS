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
    public function bdeliveries(): string
    {
        return view('Branch-only/bdeliveries');
    }
    public function bpurchaserequest(): string
    {
        return view('Branch-only/bpurchaserequest');
    }
    public function bsettings(): string
    {
        return view('Branch-only/bsettings');
    }
    public function btransfer(): string
    {
        return view('Branch-only/btransfer');
    }
    public function logout()
    {
        return redirect()->to(base_url('login'));
    }
    
    
}
