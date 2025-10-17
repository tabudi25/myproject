<?php

namespace App\Controllers;

use App\Models\DeliveryConfirmationModel;
use App\Models\OrderModel;
use App\Models\AnimalModel;
use App\Models\UserModel;
use App\Models\NotificationModel;

class DeliveryController extends BaseController
{
    protected $deliveryModel;
    protected $orderModel;
    protected $animalModel;
    protected $userModel;
    protected $notificationModel;
    protected $db;

    public function __construct()
    {
        $this->deliveryModel = new DeliveryConfirmationModel();
        $this->orderModel = new OrderModel();
        $this->animalModel = new AnimalModel();
        $this->userModel = new UserModel();
        $this->notificationModel = new NotificationModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Staff: Show delivery confirmation form
     */
    public function index()
    {
        // Check if user is staff
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['staff', 'admin'])) {
            return redirect()->to('/login')->with('msg', 'Staff access required');
        }

        try {
            $staffId = session()->get('user_id');
            log_message('info', 'Loading deliveries for staff ID: ' . $staffId);
            
            // Check if staff ID is valid
            if (!$staffId) {
                log_message('error', 'No staff ID found in session');
                return redirect()->to('/staff-dashboard')
                    ->with('msg', 'Session error. Please login again.');
            }
            
            $deliveries = $this->deliveryModel->getDeliveriesByStaff($staffId);
            log_message('info', 'Found ' . count($deliveries) . ' deliveries for staff');
            
            $data = [
                'title' => 'Delivery Confirmations',
                'deliveries' => $deliveries
            ];

            return view('staff/delivery_confirmations', $data);
        } catch (\Exception $e) {
            log_message('error', 'Delivery index error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->to('/staff-dashboard')
                ->with('msg', 'Error loading delivery confirmations. Please try again.');
        }
    }

    /**
     * Staff: Show form to create new delivery confirmation
     */
    public function create()
    {
        // Check if user is staff
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['staff', 'admin'])) {
            return redirect()->to('/login')->with('msg', 'Staff access required');
        }

        try {
            $data = [
                'title' => 'Confirm Animal Delivery',
                'orders' => $this->getAvailableOrders()
            ];

            return view('staff/delivery_form', $data);
        } catch (\Exception $e) {
            log_message('error', 'Delivery create error: ' . $e->getMessage());
            return redirect()->to('/staff/delivery-confirmations')
                ->with('msg', 'Error loading delivery form. Please try again.');
        }
    }

    /**
     * Staff: Process delivery confirmation form
     */
    public function store()
    {
        // Check if user is staff
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['staff', 'admin'])) {
            return redirect()->to('/login')->with('msg', 'Staff access required');
        }

        try {
            // Debug: Log the incoming data
            log_message('info', 'Delivery submission attempt - POST data: ' . json_encode($this->request->getPost()));
            log_message('info', 'Delivery submission attempt - FILES data: ' . json_encode($this->request->getFiles()));
            
            $rules = [
                'order_id' => 'required|integer',
                'animal_id' => 'required|integer',
                'delivery_photo' => 'uploaded[delivery_photo]|max_size[delivery_photo,5120]|ext_in[delivery_photo,jpg,jpeg,png,gif]',
                'payment_photo' => 'uploaded[payment_photo]|max_size[payment_photo,5120]|ext_in[payment_photo,jpg,jpeg,png,gif]',
                'delivery_notes' => 'permit_empty|string',
                'delivery_address' => 'required|string',
                'payment_amount' => 'required|decimal',
                'payment_method' => 'required|string'
            ];

            if (!$this->validate($rules)) {
                $errors = $this->validator->getErrors();
                log_message('error', 'Delivery validation failed: ' . json_encode($errors));
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $errors);
            }

            // Get order details
            $order = $this->orderModel->find($this->request->getPost('order_id'));
            if (!$order) {
                return redirect()->back()->with('msg', 'Order not found');
            }
            
            // Convert to array if it's an object
            if (is_object($order)) {
                $order = $order->toArray();
            }

            // Upload delivery photo
            $deliveryPhoto = $this->request->getFile('delivery_photo');
            $deliveryPhotoName = null;
            if ($deliveryPhoto && $deliveryPhoto->isValid() && !$deliveryPhoto->hasMoved()) {
                $deliveryPhotoName = $deliveryPhoto->getRandomName();
                if (!$deliveryPhoto->move(ROOTPATH . 'public/uploads/deliveries', $deliveryPhotoName)) {
                    return redirect()->back()->with('msg', 'Failed to upload delivery photo');
                }
            }

            // Upload payment photo
            $paymentPhoto = $this->request->getFile('payment_photo');
            $paymentPhotoName = null;
            if ($paymentPhoto && $paymentPhoto->isValid() && !$paymentPhoto->hasMoved()) {
                $paymentPhotoName = $paymentPhoto->getRandomName();
                if (!$paymentPhoto->move(ROOTPATH . 'public/uploads/payments', $paymentPhotoName)) {
                    return redirect()->back()->with('msg', 'Failed to upload payment photo');
                }
            }

            // Create delivery confirmation
            $data = [
                'order_id' => $this->request->getPost('order_id'),
                'staff_id' => session()->get('user_id'),
                'customer_id' => $order['user_id'],
                'animal_id' => $this->request->getPost('animal_id'),
                'delivery_photo' => $deliveryPhotoName,
                'payment_photo' => $paymentPhotoName,
                'delivery_notes' => $this->request->getPost('delivery_notes'),
                'delivery_address' => $this->request->getPost('delivery_address'),
                'delivery_date' => date('Y-m-d H:i:s'),
                'payment_amount' => $this->request->getPost('payment_amount'),
                'payment_method' => $this->request->getPost('payment_method'),
                'status' => 'pending'
            ];

            log_message('info', 'Attempting to insert delivery data: ' . json_encode($data));
            
            if ($deliveryId = $this->deliveryModel->insert($data)) {
                log_message('info', 'Delivery confirmation inserted successfully');
                
                // Send notification to customer
                $this->sendDeliveryReadyNotification($order['user_id'], $this->request->getPost('order_id'), $deliveryId);
                
                return redirect()->to('/staff/delivery-confirmations')
                    ->with('msg', 'Delivery confirmation submitted successfully! Customer has been notified.');
            } else {
                $errors = $this->deliveryModel->errors();
                log_message('error', 'Delivery insertion failed: ' . json_encode($errors));
                return redirect()->back()
                    ->withInput()
                    ->with('msg', 'Failed to submit delivery confirmation: ' . implode(', ', $errors));
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Delivery store error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('msg', 'Error submitting delivery confirmation: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Show all delivery confirmations
     */
    public function adminIndex()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('msg', 'Admin access required');
        }

        $data = [
            'title' => 'Delivery Confirmations - Admin',
            'deliveries' => $this->deliveryModel->getDeliveriesWithDetails()
        ];

        return view('admin/delivery_confirmations', $data);
    }

    /**
     * Admin: Update delivery status
     */
    public function updateStatus()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('msg', 'Admin access required');
        }

        $id = $this->request->getPost('delivery_id');
        $status = $this->request->getPost('status');
        $adminNotes = $this->request->getPost('admin_notes');

        if ($this->deliveryModel->updateDeliveryStatus($id, $status, $adminNotes)) {
            return redirect()->to('/fluffy-admin/delivery-confirmations')
                ->with('msg', 'Delivery status updated successfully');
        } else {
            return redirect()->back()->with('msg', 'Failed to update delivery status');
        }
    }

    /**
     * Get available orders for delivery confirmation
     */
    private function getAvailableOrders()
    {
        // Get orders that are confirmed and ready for delivery
        $builder = $this->db->table('orders o');
        $builder->select('o.*, u.name as customer_name');
        $builder->join('users u', 'o.user_id = u.id', 'left');
        $builder->where('o.status', 'confirmed');
        $builder->where('o.payment_status', 'paid');
        $builder->orderBy('o.created_at', 'DESC');

        $result = $builder->get();
        return $result ? $result->getResultArray() : [];
    }

    /**
     * API: Get order details for delivery form
     */
    public function getOrderDetails()
    {
        $orderId = $this->request->getPost('order_id');
        
        // Get order with customer details
        $builder = $this->db->table('orders o');
        $builder->select('o.*, u.name as customer_name');
        $builder->join('users u', 'o.user_id = u.id', 'left');
        $builder->where('o.id', $orderId);
        $order = $builder->get()->getRowArray();
        
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        // Get order items with animal details
        $builder = $this->db->table('order_items oi');
        $builder->select('oi.*, a.name as animal_name, a.image as animal_image');
        $builder->join('animals a', 'oi.animal_id = a.id', 'left');
        $builder->where('oi.order_id', $orderId);
        $items = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'order' => $order,
            'items' => $items
        ]);
    }

    /**
     * Send delivery ready notification to customer
     */
    private function sendDeliveryReadyNotification($customerId, $orderId, $deliveryId)
    {
        try {
            // Get animal name for notification
            $animal = $this->animalModel->find($this->request->getPost('animal_id'));
            $animalName = $animal ? (is_array($animal) ? $animal['name'] : $animal->name) : 'your animal';
            
            // Create notification
            $this->notificationModel->createDeliveryReadyNotification(
                $customerId,
                $orderId,
                $deliveryId,
                $animalName
            );
            
            log_message('info', "Delivery ready notification sent to customer ID: {$customerId}");
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to send delivery notification: ' . $e->getMessage());
        }
    }
}
