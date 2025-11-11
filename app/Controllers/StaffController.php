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
        
        // Get dashboard stats with proper error handling - delivered orders only
        $paidTotal = $this->orderModel
            ->selectSum('total_amount')
            ->where('status', 'delivered')
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
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
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
            ->asArray()
            ->findAll();

        // Get order items (pets) for each order
        $orderItemModel = new \App\Models\OrderItemModel();
        foreach ($orders as &$order) {
            $orderId = $order['id'];
            $items = $orderItemModel->select('order_items.*, animals.name as animal_name, categories.name as category_name')
                                    ->join('animals', 'animals.id = order_items.animal_id')
                                    ->join('categories', 'categories.id = animals.category_id', 'left')
                                    ->where('order_items.order_id', $orderId)
                                    ->asArray()
                                    ->findAll();
            
            // Create formatted pet information for display
            if (!empty($items)) {
                $petInfo = [];
                foreach ($items as $item) {
                    $petName = $item['animal_name'] ?? 'Unknown Pet';
                    $category = $item['category_name'] ?? 'Unknown Category';
                    $petInfo[] = $petName . ' (' . $category . ')';
                }
                $order['pets_display'] = implode(', ', $petInfo);
                // Also store first pet name and category for easy access
                $order['pet_name'] = $items[0]['animal_name'] ?? 'N/A';
                $order['pet_category'] = $items[0]['category_name'] ?? 'N/A';
            } else {
                $order['pets_display'] = 'N/A';
                $order['pet_name'] = 'N/A';
                $order['pet_category'] = 'N/A';
            }
            
            // Reset the query builder for next iteration
            $orderItemModel->resetQuery();
        }

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
            // Get the previous order status to check if this is a status change
            $previousStatus = $order['status'] ?? 'pending';
            
            // Update animal status based on order status
            $orderItems = $this->db->table('order_items')->where('order_id', $id)->get()->getResultArray();
            $animalModel = new \App\Models\AnimalModel();
            
            // Mark as 'sold' when order is delivered (pets are actually delivered to customer)
            if ($status === 'delivered' && $previousStatus !== 'delivered') {
                foreach ($orderItems as $item) {
                    // Update from 'reserved' to 'sold' when delivered
                    $updateResult = $animalModel->update($item['animal_id'], ['status' => 'sold']);
                    log_message('info', 'Updating animal ID ' . $item['animal_id'] . ' to sold status. Result: ' . ($updateResult ? 'success' : 'failed'));
                }
            }
            // Mark as 'sold' when staff confirms the order (status changes to 'confirmed')
            // This ensures pets are marked as sold when confirmed
            elseif ($status === 'confirmed' && $previousStatus !== 'confirmed') {
                foreach ($orderItems as $item) {
                    $animalModel->update($item['animal_id'], ['status' => 'sold']);
                }
            }
            // If order is cancelled, make animals available again
            elseif ($status === 'cancelled') {
                foreach ($orderItems as $item) {
                    $animalModel->update($item['animal_id'], ['status' => 'available']);
                }
            }
            
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
            ->where('status', 'delivered');

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
            ->where('status', 'delivered');

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

    public function getSalesStats()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'staff') {
            return $this->response->setJSON(['success' => false, 'message' => 'Staff access required']);
        }

        // Get period from request
        $period = $this->request->getGet('period') ?: 'today';
        
        // Calculate date range based on period
        $endDate = date('Y-m-d');
        switch ($period) {
            case 'today':
                $startDate = date('Y-m-d');
                break;
            case 'week':
                $startDate = date('Y-m-d', strtotime('-7 days'));
                break;
            case 'month':
                $startDate = date('Y-m-01');
                break;
            case 'year':
                $startDate = date('Y-01-01');
                break;
            default:
                $startDate = date('Y-m-d');
        }

        // Get statistics for delivered orders
        $totalAdoptions = $this->orderModel
            ->where('status', 'delivered')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->countAllResults();

        $totalSales = $this->orderModel
            ->selectSum('total_amount')
            ->where('status', 'delivered')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate . ' 23:59:59')
            ->first();

        $averageSale = $totalAdoptions > 0 ? (float)($totalSales['total_amount'] ?? 0) / $totalAdoptions : 0;

        return $this->response->setJSON([
            'success' => true,
            'stats' => [
                'total_adoptions' => $totalAdoptions,
                'total_sales' => (float)($totalSales['total_amount'] ?? 0),
                'average_sale' => $averageSale
            ]
        ]);
    }

    public function getSalesData()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'staff') {
            return $this->response->setJSON(['success' => false, 'message' => 'Staff access required']);
        }

        $period = $this->request->getGet('period') ?: 'today';
        // Map 'today' to 'day' for consistency
        if ($period === 'today') {
            $period = 'day';
        }
        
        $labels = [];
        $sales = [];
        $orders = [];

        // Calculate date range for category sales
        $endDate = date('Y-m-d');
        switch ($period) {
            case 'day':
                $startDate = date('Y-m-d');
                break;
            case 'week':
                $startDate = date('Y-m-d', strtotime('-7 days'));
                break;
            case 'month':
                $startDate = date('Y-m-01');
                break;
            case 'year':
                $startDate = date('Y-01-01');
                break;
            default:
                $startDate = date('Y-m-d');
        }

        switch ($period) {
            case 'day':
                // Hourly data for today
                for ($i = 0; $i < 24; $i++) {
                    $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $labels[] = $hour . ':00';
                    
                    $hourSales = $this->orderModel
                        ->selectSum('total_amount')
                        ->where('DATE(created_at)', date('Y-m-d'))
                        ->where('HOUR(created_at)', $i)
                        ->where('status', 'delivered')
                        ->first();
                    
                    $sales[] = (float)($hourSales['total_amount'] ?? 0);
                    
                    $hourOrders = $this->orderModel
                        ->where('DATE(created_at)', date('Y-m-d'))
                        ->where('HOUR(created_at)', $i)
                        ->where('status', 'delivered')
                        ->countAllResults();
                    
                    $orders[] = $hourOrders;
                }
                break;

            case 'week':
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $label = $i == 0 ? 'Today' : date('M d', strtotime("-$i days"));
                    
                    $labels[] = $label;
                    
                    $daySales = $this->orderModel
                        ->selectSum('total_amount')
                        ->where('DATE(created_at)', $date)
                        ->where('status', 'delivered')
                        ->first();
                    
                    $sales[] = (float)($daySales['total_amount'] ?? 0);
                    
                    $dayOrders = $this->orderModel
                        ->where('DATE(created_at)', $date)
                        ->where('status', 'delivered')
                        ->countAllResults();
                    
                    $orders[] = $dayOrders;
                }
                break;

            case 'month':
                // Last 30 days grouped by week
                for ($i = 3; $i >= 0; $i--) {
                    $weekStart = date('Y-m-d', strtotime("-" . (($i + 1) * 7) . " days"));
                    $weekEnd = date('Y-m-d', strtotime("-" . ($i * 7) . " days"));
                    
                    $labels[] = date('M d', strtotime($weekStart)) . ' - ' . date('M d', strtotime($weekEnd));
                    
                    $weekSales = $this->orderModel
                        ->selectSum('total_amount')
                        ->where('DATE(created_at) >=', $weekStart)
                        ->where('DATE(created_at) <=', $weekEnd)
                        ->where('status', 'delivered')
                        ->first();
                    
                    $sales[] = (float)($weekSales['total_amount'] ?? 0);
                    
                    $weekOrders = $this->orderModel
                        ->where('DATE(created_at) >=', $weekStart)
                        ->where('DATE(created_at) <=', $weekEnd)
                        ->where('status', 'delivered')
                        ->countAllResults();
                    
                    $orders[] = $weekOrders;
                }
                break;

            case 'year':
                // Last 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $month = date('Y-m', strtotime("-$i months"));
                    $labels[] = date('M Y', strtotime("-$i months"));
                    
                    $monthSales = $this->orderModel
                        ->selectSum('total_amount')
                        ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                        ->where('status', 'delivered')
                        ->first();
                    
                    $sales[] = (float)($monthSales['total_amount'] ?? 0);
                    
                    $monthOrders = $this->orderModel
                        ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                        ->where('status', 'delivered')
                        ->countAllResults();
                    
                    $orders[] = $monthOrders;
                }
                break;
        }

        // Get sales by category for the selected period
        $db = \Config\Database::connect();
        $builder = $db->table('orders');
        $categorySales = $builder
            ->select('categories.name as category_name, SUM(order_items.price * order_items.quantity) as total_sales')
            ->join('order_items', 'order_items.order_id = orders.id', 'inner')
            ->join('animals', 'animals.id = order_items.animal_id', 'inner')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->where('orders.status', 'delivered')
            ->where('orders.created_at >=', $startDate)
            ->where('orders.created_at <=', $endDate . ' 23:59:59')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'DESC')
            ->get()
            ->getResultArray();

        $categoryLabels = [];
        $categoryData = [];
        $categoryColors = ['#ff6b35', '#f7931e', '#2c3e50', '#28a745', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14'];
        
        if ($categorySales && is_array($categorySales)) {
            foreach ($categorySales as $index => $catSale) {
                $catName = $catSale['category_name'] ?? 'Uncategorized';
                $catSales = (float)($catSale['total_sales'] ?? 0);
                if ($catSales > 0) {
                    $categoryLabels[] = $catName;
                    $categoryData[] = $catSales;
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'labels' => $labels ?? [],
            'sales' => $sales ?? [],
            'orders' => $orders ?? [],
            'period' => $period,
            'categoryLabels' => $categoryLabels,
            'categoryData' => $categoryData,
            'categoryColors' => count($categoryLabels) > 0 ? array_slice($categoryColors, 0, count($categoryLabels)) : $categoryColors
        ]);
    }

    public function getCompletedDeliveries()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'staff') {
            return $this->response->setJSON(['success' => false, 'message' => 'Staff access required']);
        }

        // Get period from request - use 'all' to show all completed deliveries
        $period = $this->request->getGet('period') ?: 'all';
        
        // Build query for completed deliveries (orders with 'delivered' status)
        $query = $this->orderModel
            ->select('orders.id, orders.order_number, orders.total_amount as amount, orders.created_at as delivery_date, users.name as customer_name, animals.name as animal_name')
            ->join('users', 'users.id = orders.user_id')
            ->join('order_items', 'order_items.order_id = orders.id')
            ->join('animals', 'animals.id = order_items.animal_id')
            ->where('orders.status', 'delivered');
        
        // Only apply date filter if period is not 'all'
        if ($period !== 'all') {
            $endDate = date('Y-m-d');
            switch ($period) {
                case 'today':
                    $startDate = date('Y-m-d');
                    break;
                case 'week':
                    $startDate = date('Y-m-d', strtotime('-7 days'));
                    break;
                case 'month':
                    $startDate = date('Y-m-01');
                    break;
                case 'year':
                    $startDate = date('Y-01-01');
                    break;
                default:
                    $startDate = date('Y-m-d');
            }
            $query->where('orders.created_at >=', $startDate)
                  ->where('orders.created_at <=', $endDate . ' 23:59:59');
        }
        
        $deliveries = $query->orderBy('orders.created_at', 'DESC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'deliveries' => $deliveries
        ]);
    }

    public function getMyDeliveries()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'staff') {
            return $this->response->setJSON(['success' => false, 'message' => 'Staff access required']);
        }

        try {
            $staffId = session()->get('user_id');
            
            if (!$staffId) {
                log_message('error', 'No staff ID found in session');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Staff ID not found in session',
                    'data' => []
                ]);
            }
            
            $deliveryModel = new \App\Models\DeliveryConfirmationModel();
            $deliveries = $deliveryModel->getDeliveriesByStaff($staffId);
            
            // Ensure deliveries is an array
            if (!is_array($deliveries)) {
                $deliveries = [];
            }
            
            // Format deliveries for JSON response
            $formattedDeliveries = [];
            if (!empty($deliveries)) {
                $formattedDeliveries = array_map(function($delivery) {
                    // Use payment_amount from delivery_confirmations, fallback to order_total if not available
                    $paymentAmount = $delivery['payment_amount'] ?? $delivery['order_total'] ?? 0;
                    
                    return [
                        'id' => $delivery['id'] ?? null,
                        'order_id' => $delivery['order_id'] ?? null,
                        'order_number' => $delivery['order_number'] ?? null,
                        'customer_name' => $delivery['customer_name'] ?? 'N/A',
                        'animal_name' => $delivery['animal_name'] ?? 'N/A',
                        'payment_amount' => $paymentAmount,
                        'payment_method' => $delivery['payment_method'] ?? 'N/A',
                        'status' => $delivery['status'] ?? 'pending',
                        'created_at' => $delivery['created_at'] ?? date('Y-m-d H:i:s'),
                        'delivery_photo' => $delivery['delivery_photo'] ?? null,
                        'payment_photo' => $delivery['payment_photo'] ?? null,
                        'delivery_address' => $delivery['delivery_address'] ?? 'N/A',
                        'delivery_notes' => $delivery['delivery_notes'] ?? null,
                        'admin_notes' => $delivery['admin_notes'] ?? null
                    ];
                }, $deliveries);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $formattedDeliveries,
                'count' => count($formattedDeliveries)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getMyDeliveries: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error retrieving deliveries: ' . $e->getMessage()
            ]);
        }
    }

    public function getAvailableOrders()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'staff') {
            return $this->response->setJSON(['success' => false, 'message' => 'Staff access required']);
        }

        try {
            // Show ALL customer orders (both pickup and delivery) that are not cancelled or completed
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
            $orders = $result ? $result->getResultArray() : [];

            return $this->response->setJSON([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getAvailableOrders: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error retrieving orders: ' . $e->getMessage()
            ]);
        }
    }

    public function getOrderDetailsForDelivery()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'staff') {
            return $this->response->setJSON(['success' => false, 'message' => 'Staff access required']);
        }

        $orderId = $this->request->getPost('order_id') ?? $this->request->getVar('order_id');
        
        if (!$orderId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order ID is required']);
        }

        try {
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
        } catch (\Exception $e) {
            log_message('error', 'Error in getOrderDetailsForDelivery: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error retrieving order details: ' . $e->getMessage()
            ]);
        }
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

