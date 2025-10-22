<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'delivery_type',
        'delivery_address',
        'delivery_fee',
        'payment_method',
        'payment_status',
        'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'order_number'    => 'required|max_length[50]|is_unique[orders.order_number,id,{id}]',
        'user_id'         => 'required|integer',
        'total_amount'    => 'required|decimal|greater_than[0]',
        'status'          => 'in_list[pending,confirmed,processing,shipped,delivered,cancelled]',
        'delivery_type'   => 'required|in_list[pickup,delivery]',
        'delivery_fee'    => 'decimal|greater_than_equal_to[0]',
        'payment_method'  => 'in_list[cod,gcash,bank_transfer]',
        'payment_status'  => 'in_list[pending,paid,failed]'
    ];
    
    protected $validationMessages = [
        'order_number' => [
            'required' => 'Order number is required',
            'is_unique' => 'This order number already exists'
        ],
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be a valid number'
        ],
        'total_amount' => [
            'required' => 'Total amount is required',
            'decimal' => 'Total amount must be a valid decimal number',
            'greater_than' => 'Total amount must be greater than 0'
        ],
        'delivery_type' => [
            'required' => 'Delivery type is required',
            'in_list' => 'Delivery type must be pickup or delivery'
        ]
    ];
    
    protected $skipValidation = true;

    /**
     * Generate unique order number
     */
    public function generateOrderNumber()
    {
        $prefix = 'FP';
        $timestamp = date('ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        do {
            $orderNumber = $prefix . $timestamp . $random;
            $exists = $this->where('order_number', $orderNumber)->first();
            if ($exists) {
                $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        } while ($exists);
        
        return $orderNumber;
    }

    /**
     * Get orders with user details
     */
    public function getOrdersWithUser($limit = null)
    {
        $builder = $this->select('orders.*, users.name as customer_name, users.email as customer_email')
                        ->join('users', 'users.id = orders.user_id')
                        ->orderBy('orders.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    /**
     * Get user's orders
     */
    public function getUserOrders($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get order with items
     */
    public function getOrderWithItems($orderId)
    {
        $order = $this->select('orders.*, users.name as customer_name, users.email as customer_email')
                      ->join('users', 'users.id = orders.user_id')
                      ->find($orderId);
        
        if ($order) {
            $orderItemModel = new OrderItemModel();
            $order['items'] = $orderItemModel->getOrderItems($orderId);
        }
        
        return $order;
    }

    /**
     * Create order from cart
     */
    public function createFromCart($userId, $orderData)
    {
        $cartModel = new CartModel();
        $cartItems = $cartModel->getUserCart($userId);
        
        if (empty($cartItems)) {
            return false;
        }
        
        // Calculate total
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $deliveryFee = $orderData['delivery_type'] === 'delivery' ? ($orderData['delivery_fee'] ?? 100) : 0;
        $total = $subtotal + $deliveryFee;
        
        // Create order
        $orderNumber = $this->generateOrderNumber();
        $orderData['order_number'] = $orderNumber;
        $orderData['user_id'] = $userId;
        $orderData['total_amount'] = $total;
        $orderData['delivery_fee'] = $deliveryFee;
        
        if ($this->save($orderData)) {
            $orderId = $this->getInsertID();
            
            // Create order items
            $orderItemModel = new OrderItemModel();
            foreach ($cartItems as $item) {
                $orderItemModel->save([
                    'order_id' => $orderId,
                    'animal_id' => $item['animal_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
                
                // Update animal status to reserved/sold
                $animalModel = new AnimalModel();
                $animalModel->update($item['animal_id'], ['status' => 'reserved']);
            }
            
            // Clear cart
            $cartModel->clearCart($userId);
            
            return $orderId;
        }
        
        return false;
    }

    /**
     * Update order status
     */
    public function updateStatus($orderId, $status, $notes = null)
    {
        $data = ['status' => $status];
        if ($notes) {
            $data['notes'] = $notes;
        }
        
        $result = $this->update($orderId, $data);
        
        // If order is cancelled, make animals available again
        if ($status === 'cancelled' && $result) {
            $orderItemModel = new OrderItemModel();
            $items = $orderItemModel->where('order_id', $orderId)->findAll();
            
            $animalModel = new AnimalModel();
            foreach ($items as $item) {
                $animalModel->update($item['animal_id'], ['status' => 'available']);
            }
        }
        
        return $result;
    }

    /**
     * Get order statistics
     */
    public function getOrderStats()
    {
        $stats = [];
        
        $stats['total_orders'] = $this->countAll();
        $stats['pending_orders'] = $this->where('status', 'pending')->countAllResults();
        $stats['confirmed_orders'] = $this->where('status', 'confirmed')->countAllResults();
        $stats['delivered_orders'] = $this->where('status', 'delivered')->countAllResults();
        
        $totalRevenue = $this->selectSum('total_amount')
                             ->where('status !=', 'cancelled')
                             ->first();
        $stats['total_revenue'] = $totalRevenue['total_amount'] ?? 0;
        
        return $stats;
    }
}