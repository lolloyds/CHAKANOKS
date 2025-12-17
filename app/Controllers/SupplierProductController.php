<?php

namespace App\Controllers;

use App\Models\SupplierProductModel;
use App\Models\ItemModel;

class SupplierProductController extends BaseController
{
    protected $supplierProductModel;
    protected $itemModel;

    public function __construct()
    {
        $this->supplierProductModel = new SupplierProductModel();
        $this->itemModel = new ItemModel();
    }

    /**
     * Check if user is a supplier and get their supplier ID
     */
    private function checkSupplierAccess()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'Supplier' || !$user['supplier_id']) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Access denied. Supplier access required.');
        }
        return null;
    }

    /**
     * List supplier products
     */
    public function index()
    {
        $redirect = $this->checkSupplierAccess();
        if ($redirect) return $redirect;

        $user = session()->get('user');
        $supplierId = $user['supplier_id'];

        $data = [
            'products' => $this->supplierProductModel->getSupplierProductsWithDetails($supplierId),
            'title' => 'My Products'
        ];

        return view('supplier/products/index', $data);
    }

    /**
     * Show add product form
     */
    public function create()
    {
        $redirect = $this->checkSupplierAccess();
        if ($redirect) return $redirect;

        $user = session()->get('user');
        $supplierId = $user['supplier_id'];

        $data = [
            'availableItems' => $this->supplierProductModel->getAvailableItemsForSupplier($supplierId),
            'title' => 'Add Product'
        ];

        return view('supplier/products/create', $data);
    }

    /**
     * Store new product
     */
    public function store()
    {
        $redirect = $this->checkSupplierAccess();
        if ($redirect) return $redirect;

        $user = session()->get('user');
        $supplierId = $user['supplier_id'];

        $rules = [
            'item_id' => 'required|integer',
            'price_per_unit' => 'required|decimal|greater_than[0]',
            'minimum_order' => 'required|integer|greater_than[0]',
            'availability_status' => 'required|in_list[available,out_of_stock,discontinued]',
            'lead_time_days' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'supplier_id' => $supplierId,
            'item_id' => $this->request->getPost('item_id'),
            'price_per_unit' => $this->request->getPost('price_per_unit'),
            'minimum_order' => $this->request->getPost('minimum_order'),
            'availability_status' => $this->request->getPost('availability_status'),
            'lead_time_days' => $this->request->getPost('lead_time_days'),
            'notes' => $this->request->getPost('notes'),
        ];

        if ($this->supplierProductModel->insert($data)) {
            return redirect()->to(base_url('supplier/products'))->with('success', 'Product added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add product');
        }
    }

    /**
     * Show edit product form
     */
    public function edit($id)
    {
        $redirect = $this->checkSupplierAccess();
        if ($redirect) return $redirect;

        $user = session()->get('user');
        $supplierId = $user['supplier_id'];

        // Get product and verify it belongs to this supplier
        $product = $this->supplierProductModel->find($id);
        if (!$product || $product['supplier_id'] != $supplierId) {
            return redirect()->to(base_url('supplier/products'))->with('error', 'Product not found');
        }

        // Get item details
        $item = $this->itemModel->find($product['item_id']);

        $data = [
            'product' => $product,
            'item' => $item,
            'title' => 'Edit Product'
        ];

        return view('supplier/products/edit', $data);
    }

    /**
     * Update product
     */
    public function update($id)
    {
        $redirect = $this->checkSupplierAccess();
        if ($redirect) return $redirect;

        $user = session()->get('user');
        $supplierId = $user['supplier_id'];

        // Get product and verify it belongs to this supplier
        $product = $this->supplierProductModel->find($id);
        if (!$product || $product['supplier_id'] != $supplierId) {
            return redirect()->to(base_url('supplier/products'))->with('error', 'Product not found');
        }

        $rules = [
            'price_per_unit' => 'required|decimal|greater_than[0]',
            'minimum_order' => 'required|integer|greater_than[0]',
            'availability_status' => 'required|in_list[available,out_of_stock,discontinued]',
            'lead_time_days' => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'price_per_unit' => $this->request->getPost('price_per_unit'),
            'minimum_order' => $this->request->getPost('minimum_order'),
            'availability_status' => $this->request->getPost('availability_status'),
            'lead_time_days' => $this->request->getPost('lead_time_days'),
            'notes' => $this->request->getPost('notes'),
        ];

        if ($this->supplierProductModel->update($id, $data)) {
            return redirect()->to(base_url('supplier/products'))->with('success', 'Product updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update product');
        }
    }

    /**
     * Delete product
     */
    public function delete($id)
    {
        $redirect = $this->checkSupplierAccess();
        if ($redirect) return $redirect;

        $user = session()->get('user');
        $supplierId = $user['supplier_id'];

        // Get product and verify it belongs to this supplier
        $product = $this->supplierProductModel->find($id);
        if (!$product || $product['supplier_id'] != $supplierId) {
            return redirect()->to(base_url('supplier/products'))->with('error', 'Product not found');
        }

        if ($this->supplierProductModel->delete($id)) {
            return redirect()->to(base_url('supplier/products'))->with('success', 'Product removed successfully');
        } else {
            return redirect()->to(base_url('supplier/products'))->with('error', 'Failed to remove product');
        }
    }
}