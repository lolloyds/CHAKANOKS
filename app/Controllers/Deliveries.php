<?php
namespace App\Controllers;
use App\Models\DeliveryModel;
use App\Models\SupplierModel;
use CodeIgniter\Controller;

class Deliveries extends Controller
{
    public function create()
    {
        $deliveryModel = new DeliveryModel();
        $supplierModel = new SupplierModel();
        $suppliers = $supplierModel->findAll();
        if ($this->request->getMethod() === 'post') {
            $data = [
                'supplier_id'   => $this->request->getPost('supplier_id'),
                'item_name'     => $this->request->getPost('item_name'),
                'quantity'      => $this->request->getPost('quantity'),
                'delivery_date' => $this->request->getPost('delivery_date'),
                'status'        => 'Scheduled',
            ];
            $deliveryModel->save($data);
            return redirect()->to('/deliveries/success');
        }
        return view('deliveries/create', ['suppliers' => $suppliers]);
    }

    public function success()
    {
        return view('deliveries/success');
    }

    public function updateStatus($id)
    {
        $deliveryModel = new DeliveryModel();
        if ($this->request->getMethod() === 'post') {
            $deliveryModel->update($id, ['status' => 'in_transit']);
            return redirect()->to('/deliveries/status-updated');
        }
        $delivery = $deliveryModel->find($id);
        return view('deliveries/update_status', ['delivery' => $delivery]);
    }

    public function statusUpdated()
    {
        return view('deliveries/status_updated');
    }

    public function markDelivered($id)
    {
        $deliveryModel = new DeliveryModel();
        $deliveryModel->update($id, ['status' => 'delivered']);
        return redirect()->to('/deliveries/delivered-success');
    }

    public function deliveredSuccess()
    {
        return view('deliveries/delivered_success');
    }
}
