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
    public function settings(): string
    {
        return view('settings');
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
