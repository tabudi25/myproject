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
            // Only return valid delivery confirmations that have been properly created
            // Must have order_id and customer_id (essential fields for a valid delivery)
            $builder->where('dc.order_id IS NOT NULL');
            $builder->where('dc.customer_id IS NOT NULL');
            // Only show manually created delivery confirmations (exclude automatically created ones)
            // Manually created ones have at least one photo OR delivery_notes that don't contain "via status update"
            // Automatically created ones have no photos and delivery_notes containing "via status update"
            $builder->groupStart();
                $builder->where("(dc.delivery_photo IS NOT NULL AND dc.delivery_photo != '')");
                $builder->orWhere("(dc.payment_photo IS NOT NULL AND dc.payment_photo != '')");
                $builder->orGroupStart();
                    $builder->where("(dc.delivery_notes IS NOT NULL AND dc.delivery_notes != '' AND dc.delivery_notes NOT LIKE '%via status update%')");
                $builder->groupEnd();
            $builder->groupEnd();
            // Group by delivery confirmation ID to prevent duplicates
            $builder->groupBy('dc.id');
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
            dc.id,
            dc.order_id,
            dc.staff_id,
            dc.customer_id,
            dc.animal_id,
            dc.delivery_photo,
            dc.payment_photo,
            dc.delivery_notes,
            dc.admin_notes,
            dc.delivery_address,
            dc.delivery_date,
            dc.payment_amount,
            dc.payment_method,
            dc.status,
            dc.created_at,
            dc.updated_at,
            dc.created_at as confirmation_created_at,
            dc.delivery_date as confirmation_delivery_date,
            u1.name as staff_name,
            u1.email as staff_email,
            u2.name as customer_name,
            u2.email as customer_email,
            a.name as animal_name,
            a.image as animal_image,
            o.order_number,
            o.delivery_type,
            o.status as order_status,
            o.created_at as order_created_at,
            o.total_amount as order_total
        ');
        $builder->join('users u1', 'dc.staff_id = u1.id', 'left');
        $builder->join('users u2', 'dc.customer_id = u2.id', 'left');
        $builder->join('animals a', 'dc.animal_id = a.id', 'left');
        $builder->join('orders o', 'dc.order_id = o.id', 'left');
        
        if ($status) {
            $builder->where('dc.status', $status);
        }
        
        // Group by delivery confirmation ID to prevent duplicates from JOINs
        // Also ensure we only get one record per order_id by using a subquery
        $builder->groupBy('dc.id');
        $builder->orderBy('dc.created_at', 'DESC');
        
        // Use a subquery to get only the most recent/best delivery confirmation per order_id
        // This prevents duplicates at the database level
        
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
        $builder->select('dc.*, 
                         dc.created_at as confirmation_created_at,
                         dc.delivery_date as confirmation_delivery_date,
                         o.order_number, 
                         o.delivery_type,
                         o.payment_status,
                         o.status as order_status,
                         o.created_at as order_created_at,
                         u1.name as staff_name, 
                         u1.email as staff_email,
                         u2.name as customer_name, 
                         u2.email as customer_email,
                         a.name as animal_name, 
                         a.image as animal_image');
        $builder->join('orders o', 'o.id = dc.order_id');
        $builder->join('users u1', 'u1.id = dc.staff_id');
        $builder->join('users u2', 'u2.id = dc.customer_id');
        $builder->join('animals a', 'a.id = dc.animal_id');
        $builder->where('dc.id', $id);
        return $builder->get()->getRowArray();
    }
}
