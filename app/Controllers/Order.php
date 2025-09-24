<?php

namespace App\Controllers;

use App\Models\OrderModel;

class Order extends BaseController
{
    public function view_order($id = null)
    {
        $orderModel = new OrderModel();

        $order = $orderModel->find($id);

        if (!$order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Order $id not found");
        }

        return view('fluffy-planet/view_order', [
            'order' => $order
        ]);
    }
}
