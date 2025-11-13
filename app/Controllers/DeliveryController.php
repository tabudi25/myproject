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

        // Redirect to sales report with delivery tab
        return redirect()->to('/staff/sales-report?tab=delivery');
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
            return redirect()->to('/staff/sales-report?tab=delivery&msg=' . urlencode('Error loading delivery form. Please try again.'));
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
                'delivery_notes' => 'permit_empty|string',
                'delivery_address' => 'permit_empty|string',
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

            // Get the first animal from the order items
            $orderItems = $this->db->table('order_items')
                ->where('order_id', $order['id'])
                ->get()
                ->getResultArray();
            
            if (empty($orderItems)) {
                return redirect()->back()->with('msg', 'No animals found in this order');
            }
            
            $animalId = $orderItems[0]['animal_id']; // Get the first animal

            // Get delivery address - use order's delivery address if not provided
            $deliveryAddress = $this->request->getPost('delivery_address');
            if (empty($deliveryAddress) && !empty($order['delivery_address'])) {
                $deliveryAddress = $order['delivery_address'];
            }

            // Get payment amount and method
            $paymentAmount = $this->request->getPost('payment_amount');
            $paymentMethod = $this->request->getPost('payment_method');
            
            // Validate payment amount and method
            if (empty($paymentAmount) || empty($paymentMethod)) {
                $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Payment amount and payment method are required.'
                    ]);
                }
                return redirect()->back()->with('msg', 'Payment amount and payment method are required.');
            }

            // Handle file uploads
            $deliveryPhotoName = '';
            $paymentPhotoName = '';
            
            $files = $this->request->getFiles();
            
            // Handle delivery photo upload
            if (isset($files['delivery_photo']) && $files['delivery_photo']->isValid() && !$files['delivery_photo']->hasMoved()) {
                $deliveryPhoto = $files['delivery_photo'];
                $deliveryPhotoName = $deliveryPhoto->getRandomName();
                $deliveryPhoto->move(ROOTPATH . 'public/uploads/deliveries', $deliveryPhotoName);
            }
            
            // Handle payment photo upload (only if provided - not required for delivery orders)
            if (isset($files['payment_photo']) && $files['payment_photo']->isValid() && !$files['payment_photo']->hasMoved()) {
                $paymentPhoto = $files['payment_photo'];
                $paymentPhotoName = $paymentPhoto->getRandomName();
                $paymentPhoto->move(ROOTPATH . 'public/uploads/payments', $paymentPhotoName);
            }

            // Create delivery confirmation
            $data = [
                'order_id' => (int)$this->request->getPost('order_id'),
                'staff_id' => (int)session()->get('user_id'),
                'customer_id' => (int)$order['user_id'],
                'animal_id' => (int)$animalId,
                'delivery_photo' => $deliveryPhotoName,
                'payment_photo' => $paymentPhotoName,
                'delivery_notes' => $this->request->getPost('delivery_notes') ?: '',
                'delivery_address' => $deliveryAddress ?: '',
                'delivery_date' => date('Y-m-d H:i:s'),
                'payment_amount' => (float)$paymentAmount,
                'payment_method' => $paymentMethod,
                'status' => 'pending'
            ];

            log_message('info', 'Attempting to insert delivery data: ' . json_encode($data));
            
            // Check if this is an AJAX request
            $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
            
            // Use direct database insert to bypass model validation issues
            $db = \Config\Database::connect();
            $builder = $db->table('delivery_confirmations');
            
            try {
                if ($builder->insert($data)) {
                    $deliveryId = $db->insertID();
                    log_message('info', 'Delivery confirmation inserted successfully with ID: ' . $deliveryId);
                    
                    // Update order status to 'processing' when delivery confirmation is submitted
                    // This indicates the order is being processed/delivered
                    $orderUpdateData = [
                        'status' => 'processing',
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // If payment was received, also update payment status
                    if (!empty($paymentAmount) && (float)$paymentAmount > 0) {
                        $orderUpdateData['payment_status'] = 'paid';
                    }
                    
                    $db->table('orders')
                        ->where('id', $this->request->getPost('order_id'))
                        ->update($orderUpdateData);
                    
                    log_message('info', 'Order status updated to processing for order ID: ' . $this->request->getPost('order_id'));
                    
                    // Send notification to customer
                    $this->sendDeliveryReadyNotification($order['user_id'], $this->request->getPost('order_id'), $deliveryId, $animalId);
                    
                    if ($isAjax) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Delivery confirmation submitted successfully! Order status updated. Customer has been notified.'
                        ]);
                    }
                    
                    return redirect()->to('/staff/sales-report?tab=delivery&delivery_created=1&msg=' . urlencode('Delivery confirmation submitted successfully! Customer has been notified.'));
                } else {
                    throw new \Exception('Failed to insert delivery confirmation');
                }
            } catch (\Exception $dbError) {
                log_message('error', 'Delivery insertion failed: ' . $dbError->getMessage());
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to submit delivery confirmation: ' . $dbError->getMessage()
                    ]);
                }
                
                return redirect()->back()
                    ->withInput()
                    ->with('msg', 'Failed to submit delivery confirmation: ' . $dbError->getMessage());
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Delivery store error: ' . $e->getMessage());
            
            // Check if this is an AJAX request
            $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error submitting delivery confirmation: ' . $e->getMessage()
                ]);
            }
            
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
        // Show ALL customer orders (both pickup and delivery) that are not cancelled or completed
        // This ensures staff can confirm delivery for all customer orders
        $builder = $this->db->table('orders o');
        $builder->select('o.*, u.name as customer_name');
        $builder->join('users u', 'o.user_id = u.id', 'left');
        // Include both pickup and delivery orders
        $builder->whereIn('o.delivery_type', ['pickup', 'delivery']);
        // Exclude cancelled and delivered (already completed)
        $builder->whereNotIn('o.status', ['cancelled', 'delivered']);
        // Payment can be pending or paid for any method (including COD)
        $builder->whereIn('o.payment_status', ['pending', 'paid']);
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
        $builder->select('o.*, u.name as customer_name, u.email as customer_email');
        $builder->join('users u', 'o.user_id = u.id', 'left');
        $builder->where('o.id', $orderId);
        // Support both pickup and delivery orders
        $builder->whereIn('o.delivery_type', ['pickup', 'delivery']);
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
    private function sendDeliveryReadyNotification($customerId, $orderId, $deliveryId, $animalId)
    {
        try {
            // Get animal name for notification
            $animal = $this->animalModel->find($animalId);
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
