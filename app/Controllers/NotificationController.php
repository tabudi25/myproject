<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Get notifications for the current user
     */
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('msg', 'Please login to view notifications');
        }

        $userId = session()->get('user_id');
        $notifications = $this->notificationModel->getUserNotifications($userId, 20);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        $data = [
            'title' => 'Notifications',
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ];

        return view('customer/notifications', $data);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        
        if ($this->notificationModel->markAsRead($notificationId, $userId)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to mark as read']);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        
        if ($this->notificationModel->markAllAsRead($userId)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to mark all as read']);
        }
    }

    /**
     * Get unread notification count (API)
     */
    public function getUnreadCount()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        $count = $this->notificationModel->getUnreadCount($userId);

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $count
        ]);
    }

    /**
     * Get recent notifications (API)
     */
    public function getRecent()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        $limit = $this->request->getGet('limit') ? (int)$this->request->getGet('limit') : 10;
        $notifications = $this->notificationModel->getUserNotifications($userId, $limit);

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications
        ]);
    }
}
