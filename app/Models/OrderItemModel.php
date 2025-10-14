<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_id',
        'animal_id',
        'quantity',
        'price'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = null; // order_items table has no updated_at
    
    // Validation rules
    protected $validationRules = [
        'order_id'  => 'required|integer',
        'animal_id' => 'required|integer',
        'quantity'  => 'required|integer|greater_than[0]',
        'price'     => 'required|decimal|greater_than[0]'
    ];
    
    protected $validationMessages = [
        'order_id' => [
            'required' => 'Order ID is required',
            'integer' => 'Order ID must be a valid number'
        ],
        'animal_id' => [
            'required' => 'Animal ID is required',
            'integer' => 'Animal ID must be a valid number'
        ],
        'quantity' => [
            'required' => 'Quantity is required',
            'integer' => 'Quantity must be a valid number',
            'greater_than' => 'Quantity must be greater than 0'
        ],
        'price' => [
            'required' => 'Price is required',
            'decimal' => 'Price must be a valid decimal number',
            'greater_than' => 'Price must be greater than 0'
        ]
    ];
    
    protected $skipValidation = false;

    /**
     * Get order items with animal details
     */
    public function getOrderItems($orderId)
    {
        return $this->select('order_items.*, animals.name, animals.image, animals.gender, categories.name as category_name')
                    ->join('animals', 'animals.id = order_items.animal_id')
                    ->join('categories', 'categories.id = animals.category_id', 'left')
                    ->where('order_items.order_id', $orderId)
                    ->findAll();
    }

    /**
     * Get order subtotal
     */
    public function getOrderSubtotal($orderId)
    {
        $result = $this->selectSum('quantity * price', 'subtotal')
                       ->where('order_id', $orderId)
                       ->first();
        
        return $result['subtotal'] ?? 0;
    }
}
