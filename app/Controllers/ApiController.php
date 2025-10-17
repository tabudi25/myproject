<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\AnimalModel;
use App\Models\UserModel;
use App\Models\NotificationModel;
// use App\Models\ReservationModel; // Commented out as model doesn't exist yet
use App\Models\DeliveryConfirmationModel;

class ApiController extends BaseController
{
    protected $orderModel;
    protected $animalModel;
    protected $userModel;
    protected $notificationModel;
    // protected $reservationModel; // Commented out as model doesn't exist yet
    protected $deliveryModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->animalModel = new AnimalModel();
        $this->userModel = new UserModel();
        $this->notificationModel = new NotificationModel();
        // $this->reservationModel = new ReservationModel(); // Commented out as model doesn't exist yet
        $this->deliveryModel = new DeliveryConfirmationModel();
    }

    /**
     * Get dashboard statistics for real-time updates
     */
    public function dashboardStats()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $role = session()->get('role');
            $stats = [];

            if ($role === 'admin') {
                $stats = $this->getAdminStats();
            } elseif ($role === 'staff') {
                $stats = $this->getStaffStats();
            } elseif ($role === 'customer') {
                $stats = $this->getCustomerStats();
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $stats,
                'timestamp' => date('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Dashboard stats API error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to fetch stats'])->setStatusCode(500);
        }
    }

    /**
     * Get admin dashboard statistics
     */
    private function getAdminStats()
    {
        $db = \Config\Database::connect();
        
        // Total orders
        $totalOrders = $this->orderModel->countAll();
        $pendingOrders = $this->orderModel->where('status', 'pending')->countAllResults();
        $completedOrders = $this->orderModel->where('status', 'completed')->countAllResults();
        
        // Today's orders
        $todayOrders = $this->orderModel
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();
        
        // Available animals
        $availableAnimals = $this->animalModel->where('status', 'available')->countAllResults();
        
        // Total users
        $totalUsers = $this->userModel->countAll();
        $customerUsers = $this->userModel->where('role', 'customer')->countAllResults();
        
        // Pending reservations (placeholder - reservation system not implemented yet)
        $pendingReservations = 0; // $this->reservationModel->where('status', 'pending')->countAllResults();
        
        // Pending deliveries
        $pendingDeliveries = $this->deliveryModel->where('status', 'pending')->countAllResults();
        
        // Monthly revenue
        $monthlyRevenue = $this->orderModel
            ->select('SUM(total_amount) as revenue')
            ->where('status', 'completed')
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->get()
            ->getRow();
        
        return [
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'today_orders' => $todayOrders,
            'available_animals' => $availableAnimals,
            'total_users' => $totalUsers,
            'customer_users' => $customerUsers,
            'pending_reservations' => $pendingReservations,
            'pending_deliveries' => $pendingDeliveries,
            'monthly_revenue' => $monthlyRevenue ? $monthlyRevenue->revenue : 0
        ];
    }

    /**
     * Get staff dashboard statistics
     */
    private function getStaffStats()
    {
        // Pending orders
        $pendingOrders = $this->orderModel->where('status', 'pending')->countAllResults();
        
        // Today's orders
        $todayOrders = $this->orderModel
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();
        
        // Available animals
        $availableAnimals = $this->animalModel->where('status', 'available')->countAllResults();
        
        // Pending reservations (placeholder - reservation system not implemented yet)
        $pendingReservations = 0; // $this->reservationModel->where('status', 'pending')->countAllResults();
        
        // Pending delivery confirmations
        $pendingDeliveries = $this->deliveryModel->where('status', 'pending')->countAllResults();
        
        return [
            'pending_orders' => $pendingOrders,
            'today_orders' => $todayOrders,
            'available_animals' => $availableAnimals,
            'pending_reservations' => $pendingReservations,
            'pending_deliveries' => $pendingDeliveries
        ];
    }

    /**
     * Get customer dashboard statistics
     */
    private function getCustomerStats()
    {
        $userId = session()->get('user_id');
        
        // Customer's orders
        $totalOrders = $this->orderModel->where('user_id', $userId)->countAllResults();
        $pendingOrders = $this->orderModel->where('user_id', $userId)->where('status', 'pending')->countAllResults();
        $completedOrders = $this->orderModel->where('user_id', $userId)->where('status', 'completed')->countAllResults();
        
        // Customer's reservations (placeholder - reservation system not implemented yet)
        $totalReservations = 0; // $this->reservationModel->where('user_id', $userId)->countAllResults();
        $pendingReservations = 0; // $this->reservationModel->where('user_id', $userId)->where('status', 'pending')->countAllResults();
        
        // Customer's notifications
        $unreadNotifications = $this->notificationModel->getUnreadCount($userId);
        $recentNotifications = $this->notificationModel->getUserNotifications($userId, 5);
        
        return [
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'total_reservations' => $totalReservations,
            'pending_reservations' => $pendingReservations,
            'unread_notifications' => $unreadNotifications,
            'recent_notifications' => $recentNotifications
        ];
    }

    /**
     * Get notifications for real-time updates
     */
    public function notifications()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        try {
            $role = session()->get('role');
            $newNotifications = 0;

            if ($role === 'admin') {
                // New orders, reservations, delivery confirmations
                $newOrders = $this->orderModel
                    ->where('status', 'pending')
                    ->where('created_at >', date('Y-m-d H:i:s', strtotime('-1 hour')))
                    ->countAllResults();
                
                $newReservations = 0; // $this->reservationModel->where('status', 'pending')->where('created_at >', date('Y-m-d H:i:s', strtotime('-1 hour')))->countAllResults();
                
                $newDeliveries = $this->deliveryModel
                    ->where('status', 'pending')
                    ->where('created_at >', date('Y-m-d H:i:s', strtotime('-1 hour')))
                    ->countAllResults();
                
                $newNotifications = $newOrders + $newReservations + $newDeliveries;
                
            } elseif ($role === 'staff') {
                // New orders and reservations
                $newOrders = $this->orderModel
                    ->where('status', 'pending')
                    ->where('created_at >', date('Y-m-d H:i:s', strtotime('-1 hour')))
                    ->countAllResults();
                
                $newReservations = 0; // $this->reservationModel->where('status', 'pending')->where('created_at >', date('Y-m-d H:i:s', strtotime('-1 hour')))->countAllResults();
                
                $newNotifications = $newOrders + $newReservations;
                
            } elseif ($role === 'customer') {
                // Order status updates, delivery notifications
                $userId = session()->get('user_id');
                
                $orderUpdates = $this->orderModel
                    ->where('user_id', $userId)
                    ->where('updated_at >', date('Y-m-d H:i:s', strtotime('-1 hour')))
                    ->countAllResults();
                
                $newNotifications = $orderUpdates;
            }

            return $this->response->setJSON([
                'success' => true,
                'new_notifications' => $newNotifications,
                'timestamp' => date('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Notifications API error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to fetch notifications'])->setStatusCode(500);
        }
    }
}
