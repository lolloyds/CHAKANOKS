<?php

namespace App\Controllers;
use App\Models\SupplierModel;

class Suppliers extends BaseController
{
    public function index()
    {
        $model = new SupplierModel();

        $data['stats'] = $model->getStats();
        $data['suppliers'] = $model->orderBy('id', 'DESC')->findAll();

        return view('suppliers', $data);
    }

    public function edit($id)
    {
        $model = new SupplierModel();
        $data['supplier'] = $model->find($id);
        return view('edit_supplier', $data);
    }

    public function update($id)
    {
        $model = new SupplierModel();
        $data = [
            'supplier_name' => $this->request->getPost('supplier_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'supply_type' => $this->request->getPost('supply_type'),
            'status' => $this->request->getPost('status')
        ];
        $model->update($id, $data);
        return redirect()->to('/suppliers');
    }

    public function delete($id)
    {
        $model = new SupplierModel();
        $model->delete($id);
        return redirect()->to('/suppliers');
    }
}
