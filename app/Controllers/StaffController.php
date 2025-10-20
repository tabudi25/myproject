<?php
namespace App\Controllers;

use App\Models\AnimalModel;
use App\Models\CategoryModel;
use App\Models\orderModel;
use App\Models\userModel;
use CodeIgniter\Controller;

class StaffController extends Controller
{
    protected $animalModel;
    protected $categoryModel;
    protected $orderModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->animalModel = new AnimalModel();
        $this->categoryModel = new CategoryModel();
        $this->orderModel = new orderModel();
        $this->userModel = new userModel();
        $this->db = \Config\Database::connect();
    }

    // ==================== PAGE VIEWS ====================
    
    public function index()
    {
        // Check if user is staff
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'staff') {
            return redirect()->to('/login')->with('msg', 'Staff access required');
        }

        $data['staff'] = session()->get();
        
        // Get dashboard stats with proper error handling
        $paidTotal = $this->orderModel
            ->selectSum('total_amount')
            ->where('payment_status', 'paid')
            ->first();

        $data['stats'] = [
            'total_animals' => $this->animalModel->countAll(),
            'available_animals' => $this->animalModel->where('status', 'available')->countAllResults(),
            'pending_orders' => $this->orderModel->where('status', 'pending')->countAllResults(),
            'today_orders' => $this->orderModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults(),
            'total_inquiries' => $this->getTableCount('inquiries', 'status !=', 'closed'),
            'pending_reservations' => $this->getTableCount('reservations', 'status', 'pending'),
            'pending_animals' => $this->getTableCount('pending_animals', 'status', 'pending'),
            'total_payments' => (float)($paidTotal['total_amount'] ?? 0),
            'completed_orders' => $this->orderModel->where('status', 'delivered')->countAllResults(),
            'processing_orders' => $this->orderModel->where('status', 'processing')->countAllResults(),
            'shipped_orders' => $this->orderModel->where('status', 'shipped')->countAllResults(),
        ];
        
        return view('staff/dashboard', $data);
    }

    public function animalsPage()
    {
        $data['categories'] = $this->categoryModel->findAll();
        return view('staff/animals', $data);
    }

    public function addAnimalPage()
    {
        $data['categories'] = $this->categoryModel->findAll();
        return view('staff/add_animal', $data);
    }

    public function inquiriesPage()
    {
        return view('staff/inquiries');
    }

    public function reservationsPage()
    {
        return view('staff/reservations');
    }

    public function ordersPage()
    {
        return view('staff/orders');
    }

    public function salesReportPage()
    {
        return view('staff/sales_report');
    }

    public function paymentsPage()
    {
        return view('staff/payments');
    }

    // ==================== ANIMALS MANAGEMENT ====================
    
    public function getAnimals()
    {
        $animals = $this->animalModel
            ->select('animals.*, categories.name as category_name')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->where('animals.status', 'available')
            ->orderBy('animals.created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $animals]);
    }

    public function updateAnimal($id)
    {
        $animal = $this->animalModel->find($id);
        if (!$animal) {
            return $this->response->setJSON(['success' => false, 'message' => 'Animal not found']);
        }

        $name = trim($this->request->getPost('name') ?: $this->request->getVar('name'));
        $category_id = $this->request->getPost('category_id') ?: $this->request->getVar('category_id');
        $age = $this->request->getPost('age') ?: $this->request->getVar('age');
        $gender = $this->request->getPost('gender') ?: $this->request->getVar('gender');
        $price = $this->request->getPost('price') ?: $this->request->getVar('price');
        $description = $this->request->getPost('description') ?: $this->request->getVar('description');

        if (empty($name)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Name is required']);
        }

        $data = [
            'name' => $name,
            'category_id' => $category_id,
            'age' => $age,
            'gender' => $gender,
            'price' => $price,
            'description' => $description
        ];

        // Handle file upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads', $imageName);
            $data['image'] = $imageName;

            if (!empty($animal['image']) && file_exists(ROOTPATH . 'public/uploads/' . $animal['image'])) {
                @unlink(ROOTPATH . 'public/uploads/' . $animal['image']);
            }
        }

        if ($this->animalModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Animal updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update animal']);
        }
    }

    public function addAnimalForApproval()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name' => 'required|min_length[2]',
            'category_id' => 'required|numeric',
            'age' => 'required|numeric',
            'gender' => 'required|in_list[male,female]',
            'price' => 'required|numeric',
            'image' => 'uploaded[image]|is_image[image]|max_size[image,2048]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'message' => $validation->getErrors()]);
        }

        $image = $this->request->getFile('image');
        $imageName = $image->getRandomName();
        $image->move(ROOTPATH . 'public/uploads', $imageName);

        $data = [
            'name' => $this->request->getPost('name'),
            'category_id' => $this->request->getPost('category_id'),
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'price' => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'image' => $imageName,
            'added_by' => session()->get('user_id'),
            'status' => 'pending'
        ];

        $inserted = $this->db->table('pending_animals')->insert($data);

        if ($inserted) {
            // Send notification to all admins
            $this->sendPendingAnimalNotification($data['name'], session()->get('user_id'));
            
            return $this->response->setJSON(['success' => true, 'message' => 'Animal submitted for admin approval']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to submit animal']);
        }
    }

    /**
     * Send notification to admins about new pending animal
     */
    private function sendPendingAnimalNotification($animalName, $staffId)
    {
        try {
            // Get staff name
            $staff = $this->db->table('users')->where('id', $staffId)->get()->getRowArray();
            $staffName = $staff ? $staff['name'] : 'Unknown Staff';

            // Get all admin users
            $admins = $this->db->table('users')->where('role', 'admin')->get()->getResultArray();

            // Create notification for each admin
            foreach ($admins as $admin) {
                $notificationData = [
                    'user_id' => $admin['id'],
                    'type' => 'pending_animal',
                    'title' => 'New Animal Pending Approval',
                    'message' => "Staff member {$staffName} has submitted a new animal '{$animalName}' for approval.",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->db->table('notifications')->insert($notificationData);
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to send pending animal notification: ' . $e->getMessage());
        }
    }

    // ==================== INQUIRIES MANAGEMENT ====================
    
    public function getInquiries()
    {
        $inquiries = $this->db->table('inquiries')
            ->select('inquiries.*, animals.name as animal_name')
            ->join('animals', 'animals.id = inquiries.animal_id', 'left')
            ->orderBy('inquiries.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['success' => true, 'data' => $inquiries]);
    }

    public function respondToInquiry($id)
    {
        $response = $this->request->getPost('response') ?: $this->request->getVar('response');
        $status = $this->request->getPost('status') ?: $this->request->getVar('status');

        if (empty($response)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Response is required']);
        }

        $data = [
            'staff_response' => $response,
            'status' => $status ?: 'in_progress',
            'responded_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->db->table('inquiries')->where('id', $id)->update($data);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Response sent successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to send response']);
        }
    }

    // ==================== RESERVATIONS MANAGEMENT ====================
    
    public function getReservations()
    {
        $reservations = $this->db->table('reservations')
            ->select('reservations.*, animals.name as animal_name, animals.image, users.name as customer_name, users.email as customer_email')
            ->join('animals', 'animals.id = reservations.animal_id')
            ->join('users', 'users.id = reservations.user_id')
            ->orderBy('reservations.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['success' => true, 'data' => $reservations]);
    }

    public function confirmReservation($id)
    {
        $data = [
            'status' => 'confirmed',
            'confirmed_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->db->table('reservations')->where('id', $id)->update($data);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Reservation confirmed']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to confirm reservation']);
        }
    }

    public function cancelReservation($id)
    {
        $data = [
            'status' => 'cancelled',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->db->table('reservations')->where('id', $id)->update($data);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Reservation cancelled']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to cancel reservation']);
        }
    }

    // ==================== ORDERS MANAGEMENT ====================
    
    public function getOrders()
    {
        $orders = $this->orderModel
            ->select('orders.*, users.name as customer_name, users.email as customer_email')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $orders]);
    }

    public function confirmOrder($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        $data = [
            'status' => 'confirmed',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->db->table('orders')->where('id', $id)->update($data);

        if ($updated) {
            // Send notification to customer
            $this->sendOrderStatusNotification($order['user_id'], $order['order_number'], 'confirmed', 'Your order has been confirmed and is being prepared.');
            
            return $this->response->setJSON(['success' => true, 'message' => 'Order confirmed successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to confirm order']);
        }
    }

    public function updateOrderStatus($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        $status = $this->request->getPost('status');
        $validStatuses = ['processing', 'shipped', 'delivered'];
        
        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->db->table('orders')->where('id', $id)->update($data);

        if ($updated) {
            // Send appropriate notification to customer
            $message = $this->getStatusMessage($status, $order['delivery_type']);
            $this->sendOrderStatusNotification($order['user_id'], $order['order_number'], $status, $message);
            
            return $this->response->setJSON(['success' => true, 'message' => 'Order status updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update order status']);
        }
    }

    private function getStatusMessage($status, $deliveryType)
    {
        switch ($status) {
            case 'processing':
                return 'We are now preparing your pet for delivery. This may take some time to ensure your pet is healthy and ready.';
            case 'shipped':
                $action = $deliveryType === 'pickup' ? 'ready for pickup' : 'ready for delivery';
                return "Your order is {$action}! Please check your order tracking for more details.";
            case 'delivered':
                return 'Your order has been successfully delivered! Thank you for choosing Fluffy Planet.';
            default:
                return 'Your order status has been updated.';
        }
    }

    private function sendOrderStatusNotification($userId, $orderNumber, $status, $message)
    {
        try {
            $notificationData = [
                'user_id' => $userId,
                'type' => 'order_status',
                'title' => 'Order Status Update - #' . $orderNumber,
                'message' => $message,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->db->table('notifications')->insert($notificationData);
        } catch (\Exception $e) {
            log_message('error', 'Failed to send order status notification: ' . $e->getMessage());
        }
    }

    // ==================== SALES REPORT ====================
    
    public function getSalesReport()
    {
        $period = $this->request->getGet('period') ?: 'daily';
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $builder = $this->db->table('orders')
            ->select('DATE(created_at) as date, COUNT(*) as total_orders, SUM(total_amount) as total_sales')
            ->where('status !=', 'cancelled');

        if ($period === 'daily') {
            $builder->where('DATE(created_at)', date('Y-m-d'));
        } elseif ($period === 'weekly') {
            $builder->where('created_at >=', date('Y-m-d', strtotime('-7 days')));
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $builder->where('DATE(created_at) >=', $startDate);
            $builder->where('DATE(created_at) <=', $endDate);
        }

        $builder->groupBy('DATE(created_at)');
        $reports = $builder->get()->getResultArray();

        // Get total summary
        $summary = $this->db->table('orders')
            ->select('COUNT(*) as total_orders, SUM(total_amount) as total_sales, AVG(total_amount) as avg_sale')
            ->where('status !=', 'cancelled');

        if ($period === 'daily') {
            $summary->where('DATE(created_at)', date('Y-m-d'));
        } elseif ($period === 'weekly') {
            $summary->where('created_at >=', date('Y-m-d', strtotime('-7 days')));
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $summary->where('DATE(created_at) >=', $startDate);
            $summary->where('DATE(created_at) <=', $endDate);
        }

        $summaryData = $summary->get()->getRowArray();

        return $this->response->setJSON([
            'success' => true,
            'reports' => $reports,
            'summary' => $summaryData,
            'period' => $period
        ]);
    }

    // ==================== PAYMENT STATUS ====================
    
    public function getPayments()
    {
        $payments = $this->orderModel
            ->select('orders.*, users.name as customer_name, users.email as customer_email')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $payments]);
    }

    public function updatePaymentStatus($id)
    {
        $paymentStatus = $this->request->getPost('payment_status') ?: $this->request->getVar('payment_status');

        if (empty($paymentStatus)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Payment status is required']);
        }

        $validStatuses = ['pending', 'paid', 'failed', 'refunded'];
        if (!in_array($paymentStatus, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid payment status']);
        }

        $data = [
            'payment_status' => $paymentStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->db->table('orders')->where('id', $id)->update($data);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Payment status updated']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update payment status']);
        }
    }

    // ==================== NOTIFICATIONS ====================
    
    public function getNotifications()
    {
        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        if (!$userId || !in_array($role, ['staff', 'admin'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Get all notifications for this staff member
        $notifications = $this->db->table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();

        // Get unread count
        $unreadCount = $this->db->table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->countAllResults();

        return $this->response->setJSON([
            'success' => true,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function markNotificationRead($id)
    {
        $userId = session()->get('user_id');
        
        $updated = $this->db->table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'is_read' => true,
                'read_at' => date('Y-m-d H:i:s')
            ]);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Notification marked as read']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to mark notification']);
        }
    }

    public function markAllNotificationsRead()
    {
        $userId = session()->get('user_id');
        
        $updated = $this->db->table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => date('Y-m-d H:i:s')
            ]);

        return $this->response->setJSON(['success' => true, 'message' => 'All notifications marked as read']);
    }

    // Real-time order status updates for staff
    public function getOrderUpdates()
    {
        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        if (!$userId || !in_array($role, ['staff', 'admin'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Get recent orders (last 10)
        $recentOrders = $this->db->table('orders')
            ->select('orders.*, users.name as customer_name, users.email as customer_email')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // Get pending orders count
        $pendingCount = $this->db->table('orders')
            ->where('status', 'pending')
            ->countAllResults();

        // Get today's orders count
        $todayCount = $this->db->table('orders')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        return $this->response->setJSON([
            'success' => true,
            'recentOrders' => $recentOrders,
            'pendingCount' => $pendingCount,
            'todayCount' => $todayCount,
            'timestamp' => time()
        ]);
    }

    // Helper method to safely get table counts
    private function getTableCount($table, $column = null, $value = null)
    {
        try {
            $query = $this->db->table($table);
            if ($column && $value) {
                $query->where($column, $value);
            }
            return $query->countAllResults();
        } catch (\Exception $e) {
            // Table doesn't exist or other error, return 0
            return 0;
        }
    }
}

