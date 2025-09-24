<?php

namespace App\Controllers;
use App\Models\OrderModel;

class FluffyController extends BaseController
{
    public function index()
    {
        return view('fluffy-planet/index'); 
    }

    public function cat()       { return view('fluffy-planet/cat'); }
    public function dog()       { return view('fluffy-planet/dog'); }
    public function rabbit()    { return view('fluffy-planet/rabbit'); }
    public function hamster()   { return view('fluffy-planet/hamster'); }
    public function fish()      { return view('fluffy-planet/fish'); }
    public function categories(){ return view('fluffy-planet/categories'); }
    public function newarrival(){ return view('fluffy-planet/newarrival'); }
    public function order()     { return view('fluffy-planet/order'); }
    public function petshop()   { return view('fluffy-planet/petshop'); }
    public function test_db()   { return view('fluffy-planet/test_db'); }

    // --- Show all orders sa order_transactions page ---
    public function order_transactions()
    {
        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->orderBy('id', 'DESC')->findAll(); // latest first
        return view('fluffy-planet/order_transactions', $data);
    }

    // --- Show completed orders sa history page ---
    public function history()
    {
        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->where('order_status','Completed')
                                      ->orderBy('id','ASC')
                                      ->findAll();
        return view('fluffy-planet/history', $data);
    }

    // --- Save new order as Processing ---
    public function save_order()
    {
        $orderModel = new OrderModel();

        // Get the form data
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $fullName = trim($firstName . ' ' . $lastName);
        
        // Get payment method from the active button (this would need to be added to the form)
        $paymentMethod = $this->request->getPost('payment_method') ?: 'COD';
        
        $data = [
            'customer'       => $fullName,  // Combined first and last name
            'gmail'          => $this->request->getPost('gmail'),
            'tel_number'     => $this->request->getPost('tel_number'),
            'animal'         => $this->request->getPost('animal'),  // JSON string of pets
            'address'        => $this->request->getPost('address'),
            'total'          => floatval($this->request->getPost('total')),
            'payment_method' => $paymentMethod,
            'payment_status' => 'Paid',         
            'order_status'   => 'Processing',   
            'date'           => $this->request->getPost('date') ?: date('Y-m-d H:i:s')
        ];

        // Insert the order
        if ($orderModel->insert($data)) {
            return redirect()->to('/order_transactions')
                             ->with('success', 'Order saved successfully!');
        } else {
            return redirect()->back()
                             ->with('error', 'Failed to save order. Please try again.')
                             ->withInput();
        }
    }

    // --- Confirm order: update status to Completed ---
    public function confirm_order($id = null)
    {
        if (!$id) {
            return redirect()->to('/order_transactions')
                             ->with('error','Order ID missing!');
        }

        $orderModel = new OrderModel();
        $order = $orderModel->find($id);

        if (!$order) {
            return redirect()->to('/order_transactions')
                             ->with('error','Order not found!');
        }

        $orderModel->update($id, [
            'payment_status' => 'Paid',
            'order_status'   => 'Completed'
        ]);

        return redirect()->to('/order_transactions')
                         ->with('success','Order #' . $id . ' has been marked as Completed!');
    }
}
