<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'order_id',
        'delivery_id',
        'type',
        'title',
        'message',
        'is_read'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'type'    => 'required|in_list[delivery_ready,delivery_confirmed,delivery_rejected,order_status]',
        'title'   => 'required|max_length[255]',
        'message' => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get notifications for a user
     */
    public function getUserNotifications($userId, $limit = 10, $offset = 0)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll($limit, $offset);
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('is_read', false)
                   ->countAllResults();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId)
    {
        return $this->where('id', $notificationId)
                   ->where('user_id', $userId)
                   ->set('is_read', true)
                   ->update();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        return $this->where('user_id', $userId)
                   ->set('is_read', true)
                   ->update();
    }

    /**
     * Create delivery ready notification
     */
    public function createDeliveryReadyNotification($userId, $orderId, $deliveryId, $animalName)
    {
        $data = [
            'user_id'     => $userId,
            'order_id'    => $orderId,
            'delivery_id' => $deliveryId,
            'type'        => 'delivery_ready',
            'title'       => 'Your Animal is Ready for Delivery! 🐾',
            'message'     => "Great news! Your {$animalName} is ready for delivery. Our staff will contact you soon to arrange the delivery time and location."
        ];

        return $this->insert($data);
    }

    /**
     * Create delivery confirmed notification
     */
    public function createDeliveryConfirmedNotification($userId, $orderId, $deliveryId, $animalName)
    {
        $data = [
            'user_id'     => $userId,
            'order_id'    => $orderId,
            'delivery_id' => $deliveryId,
            'type'        => 'delivery_confirmed',
            'title'       => 'Delivery Confirmed! ✅',
            'message'     => "Your {$animalName} delivery has been confirmed by our staff. You should receive your new companion soon!"
        ];

        return $this->insert($data);
    }

    /**
     * Create delivery rejected notification
     */
    public function createDeliveryRejectedNotification($userId, $orderId, $deliveryId, $animalName, $reason = '')
    {
        $message = "Unfortunately, your {$animalName} delivery could not be completed.";
        if ($reason) {
            $message .= " Reason: {$reason}";
        }
        $message .= " Please contact our support team for assistance.";

        $data = [
            'user_id'     => $userId,
            'order_id'    => $orderId,
            'delivery_id' => $deliveryId,
            'type'        => 'delivery_rejected',
            'title'       => 'Delivery Issue - Action Required ⚠️',
            'message'     => $message
        ];

        return $this->insert($data);
    }
}
