<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\StockMovementModel;

class Inventory extends BaseController
{
    public function staff()
    {
        return view('inventory_staff');
    }

    public function list()
    {
        $items = (new ItemModel())->orderBy('name', 'ASC')->findAll();
        return $this->response->setJSON(['items' => $items]);
    }

    public function updateQuantity($id)
    {
        $itemModel = new ItemModel();
        $movementModel = new StockMovementModel();

        $item = $itemModel->find($id);
        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Item not found']);
        }

        $change = (int) $this->request->getPost('change');
        $reason = (string) $this->request->getPost('reason');

        $newQty = max(0, (int) $item['quantity'] + $change);
        $itemModel->update($id, ['quantity' => $newQty]);

        $movementModel->insert([
            'item_id' => $id,
            'change_amount' => $change,
            'reason' => $reason ?: null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true, 'quantity' => $newQty]);
    }
}


