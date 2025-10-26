<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryConfirmationModel extends Model
{
    protected $table = 'delivery_confirmations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_id', 'staff_id', 'customer_id', 'animal_id',
        'delivery_photo', 'payment_photo', 'delivery_notes',
        'delivery_address', 'delivery_date', 'payment_amount',
        'payment_method', 'status', 'admin_notes'
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'order_id' => 'required|integer',
        'staff_id' => 'required|integer',
        'customer_id' => 'required|integer',
        'animal_id' => 'required|integer',
        'delivery_photo' => 'permit_empty|string|max_length[255]',
        'payment_photo' => 'permit_empty|string|max_length[255]',
        'delivery_notes' => 'permit_empty|string',
        'delivery_address' => 'permit_empty|string',
        'delivery_date' => 'permit_empty|valid_date',
        'payment_amount' => 'permit_empty|decimal',
        'payment_method' => 'permit_empty|string|max_length[50]',
        'status' => 'permit_empty|in_list[pending,confirmed,rejected]',
        'admin_notes' => 'permit_empty|string'
    ];

    protected $validationMessages = [
        'order_id' => [
            'required' => 'Order ID is required',
            'integer' => 'Order ID must be a valid integer'
        ],
        'staff_id' => [
            'required' => 'Staff ID is required',
            'integer' => 'Staff ID must be a valid integer'
        ],
        'customer_id' => [
            'required' => 'Customer ID is required',
            'integer' => 'Customer ID must be a valid integer'
        ],
        'animal_id' => [
            'required' => 'Animal ID is required',
            'integer' => 'Animal ID must be a valid integer'
        ]
    ];

    /**
     * Get delivery confirmations with related data
     */
    public function getDeliveriesByStaff($staffId)
    {
        try {
            $builder = $this->db->table('delivery_confirmations dc');
            $builder->select('
                dc.*,
                u1.name as staff_name,
                u1.email as staff_email,
                u2.name as customer_name,
                u2.email as customer_email,
                a.name as animal_name,
                a.image as animal_image,
                o.order_number,
                o.total_amount as order_total
            ');
            $builder->join('users u1', 'dc.staff_id = u1.id', 'left');
            $builder->join('users u2', 'dc.customer_id = u2.id', 'left');
            $builder->join('animals a', 'dc.animal_id = a.id', 'left');
            $builder->join('orders o', 'dc.order_id = o.id', 'left');
            $builder->where('dc.staff_id', $staffId);
            $builder->orderBy('dc.created_at', 'DESC');
            
            $result = $builder->get();
            return $result ? $result->getResultArray() : [];
        } catch (\Exception $e) {
            log_message('error', 'getDeliveriesByStaff error: ' . $e->getMessage());
            return [];
        }
    }

    public function getDeliveriesWithDetails($status = null, $limit = null, $offset = null)
    {
        $builder = $this->db->table('delivery_confirmations dc');
        $builder->select('
            dc.*,
            u1.name as staff_name,
            u1.email as staff_email,
            u2.name as customer_name,
            u2.email as customer_email,
            a.name as animal_name,
            a.image as animal_image,
            o.order_number,
            o.total_amount as order_total
        ');
        $builder->join('users u1', 'dc.staff_id = u1.id', 'left');
        $builder->join('users u2', 'dc.customer_id = u2.id', 'left');
        $builder->join('animals a', 'dc.animal_id = a.id', 'left');
        $builder->join('orders o', 'dc.order_id = o.id', 'left');
        
        if ($status) {
            $builder->where('dc.status', $status);
        }
        
        $builder->orderBy('dc.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }


    /**
     * Get delivery confirmations by order
     */
    public function getDeliveriesByOrder($orderId)
    {
        return $this->where('order_id', $orderId)->findAll();
    }

    /**
     * Get pending delivery confirmations for admin
     */
    public function getPendingDeliveries()
    {
        return $this->getDeliveriesWithDetails('pending');
    }

    /**
     * Update delivery status
     */
    public function updateDeliveryStatus($id, $status, $adminNotes = null)
    {
        $data = ['status' => $status];
        
        if ($adminNotes) {
            $data['admin_notes'] = $adminNotes;
        }
        
        return $this->update($id, $data);
    }

    public function getPendingConfirmations()
    {
        $builder = $this->db->table('delivery_confirmations dc');
        $builder->select('dc.*, o.order_number, o.delivery_type,
                         u1.name as staff_name, u1.email as staff_email,
                         u2.name as customer_name, u2.email as customer_email,
                         a.name as animal_name, a.image as animal_image');
        $builder->join('orders o', 'o.id = dc.order_id');
        $builder->join('users u1', 'u1.id = dc.staff_id');
        $builder->join('users u2', 'u2.id = dc.customer_id');
        $builder->join('animals a', 'a.id = dc.animal_id');
        $builder->where('dc.status', 'pending');
        $builder->orderBy('dc.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getConfirmationWithDetails($id)
    {
        $builder = $this->db->table('delivery_confirmations dc');
        $builder->select('dc.*, o.order_number, o.delivery_type,
                         u1.name as staff_name, u1.email as staff_email,
                         u2.name as customer_name, u2.email as customer_email,
                         a.name as animal_name, a.image as animal_image');
        $builder->join('orders o', 'o.id = dc.order_id');
        $builder->join('users u1', 'u1.id = dc.staff_id');
        $builder->join('users u2', 'u2.id = dc.customer_id');
        $builder->join('animals a', 'a.id = dc.animal_id');
        $builder->where('dc.id', $id);
        return $builder->get()->getRowArray();
    }
}
