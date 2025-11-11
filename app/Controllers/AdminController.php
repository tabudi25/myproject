<?php

namespace App\Controllers;

use App\Models\AnimalModel;
use App\Models\CategoryModel;
use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\DeliveryConfirmationModel;
use App\Models\OrderItemModel;
use App\Models\PendingAnimalModel;

class AdminController extends BaseController
{
    protected $animalModel;
    protected $categoryModel;
    protected $userModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $deliveryModel;
    protected $pendingAnimalModel;

    public function __construct()
    {
        $this->animalModel = new AnimalModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->deliveryModel = new DeliveryConfirmationModel();
        $this->pendingAnimalModel = new PendingAnimalModel();
    }

    public function animalsPage()
    {
        return view('admin/animals');
    }

    public function pendingAnimalsPage()
    {
        return view('admin/pending_animals');
    }

    public function categoriesPage()
    {
        return view('admin/categories');
    }

    public function ordersPage()
    {
        return view('admin/orders');
    }

    public function salesReport()
    {
        return view('admin/sales_report');
    }

    public function paymentsPage()
    {
        return view('admin/payments');
    }

    public function usersPage()
    {
        return view('admin/users');
    }

    public function index()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('msg', 'Admin access required');
        }

        // Get dashboard statistics
        // Total payments = sum of total_amount for delivered orders (completed orders)
        $paidTotal = $this->orderModel
            ->selectSum('total_amount')
            ->where('status', 'delivered')
            ->first();

        $stats = [
            'total_animals' => $this->animalModel->countAll(),
            'available_animals' => $this->animalModel->where('status', 'available')->countAllResults(),
            'sold_animals' => $this->animalModel->where('status', 'sold')->countAllResults(),
            'total_categories' => $this->categoryModel->countAll(),
            'active_categories' => $this->categoryModel->where('status', 'active')->countAllResults(),
            'total_users' => $this->userModel->countAll(),
            'customer_users' => $this->userModel->where('role', 'customer')->countAllResults(),
            'total_orders' => $this->orderModel->countAll(),
            'pending_orders' => $this->orderModel->where('status', 'pending')->countAllResults(),
            'confirmed_orders' => $this->orderModel->where('status', 'confirmed')->countAllResults(),
            'completed_orders' => $this->orderModel->where('status', 'delivered')->countAllResults(),
            'today_orders' => $this->orderModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults(),
            'pending_deliveries' => $this->deliveryModel->where('status', 'pending')->countAllResults(),
            'total_payments' => (float)($paidTotal['total_amount'] ?? 0)
        ];

        // Get recent orders
        $recentOrders = $this->orderModel
            ->select('orders.*, users.name as customer_name')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Get recent animals
        $recentAnimals = $this->animalModel
            ->select('animals.*, categories.name as category_name')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->orderBy('animals.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Calculate monthly revenue - delivered orders only
        $monthlyRevenue = $this->orderModel
            ->selectSum('total_amount')
            ->where('status', 'delivered')
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->first();

        $data = [
            'title' => 'Admin Dashboard - Fluffy Planet',
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'recentAnimals' => $recentAnimals,
            'monthlyRevenue' => $monthlyRevenue['total_amount'] ?? 0,
            'userName' => session()->get('user_name'),
            'userRole' => session()->get('role')
        ];

        return view('admin/dashboard', $data);
    }

    // Animal Management
    public function getAnimals()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $animals = $this->animalModel
            ->select('animals.*, categories.name as category_name')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->orderBy('animals.id', 'DESC')
            ->asArray()
            ->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $animals]);
    }

    public function createAnimal()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        // Handle file upload
        $image = $this->request->getFile('image');
        $imageName = '';
        
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads', $imageName);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid image is required']);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'category_id' => $this->request->getPost('category_id'),
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'price' => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'image' => $imageName,
            'status' => 'available'
        ];

        if ($this->animalModel->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Animal added successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add animal']);
        }
    }

    public function updateAnimal($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $animal = $this->animalModel->find($id);
        if (!$animal) {
            return $this->response->setJSON(['success' => false, 'message' => 'Animal not found']);
        }

        // Get and validate data - handle both POST and PUT methods
        $name = trim($this->request->getPost('name') ?: $this->request->getVar('name'));
        $category_id = $this->request->getPost('category_id') ?: $this->request->getVar('category_id');
        $age = $this->request->getPost('age') ?: $this->request->getVar('age');
        $gender = $this->request->getPost('gender') ?: $this->request->getVar('gender');
        $price = $this->request->getPost('price') ?: $this->request->getVar('price');
        $status = $this->request->getPost('status') ?: $this->request->getVar('status');
        $description = $this->request->getPost('description') ?: $this->request->getVar('description');

        // Basic validation
        if (empty($name)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Name is required']);
        }
        if (empty($category_id) || !is_numeric($category_id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid category is required']);
        }
        if (empty($age) || !is_numeric($age) || $age < 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid age is required']);
        }
        if (empty($gender) || !in_array($gender, ['male', 'female'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid gender is required']);
        }
        if (empty($price) || !is_numeric($price) || $price < 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid price is required']);
        }
        if (empty($status) || !in_array($status, ['available', 'sold', 'reserved'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid status is required']);
        }

        $data = [
            'name' => $name,
            'category_id' => $category_id,
            'age' => $age,
            'gender' => $gender,
            'price' => $price,
            'status' => $status,
            'description' => $description
        ];

        // Handle file upload if provided
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads', $imageName);
            $data['image'] = $imageName;

            // Delete old image if exists
            if (!empty($animal['image']) && file_exists(ROOTPATH . 'public/uploads/' . $animal['image'])) {
                @unlink(ROOTPATH . 'public/uploads/' . $animal['image']);
            }
        }

        if ($this->animalModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Animal updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update animal. Please check your data.']);
        }
    }

    public function deleteAnimal($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $animal = $this->animalModel->find($id);
        if (!$animal) {
            return $this->response->setJSON(['success' => false, 'message' => 'Animal not found']);
        }

        // Check if animal is in any orders
        $orderItems = $this->orderItemModel->where('animal_id', $id)->findAll();
        if (!empty($orderItems)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot delete animal that has orders']);
        }

        if ($this->animalModel->delete($id)) {
            // Delete image file if exists
            if (!empty($animal['image']) && file_exists(ROOTPATH . 'public/uploads/' . $animal['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $animal['image']);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Animal deleted successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete animal']);
        }
    }

    // Category Management
    public function getCategories()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $categories = $this->categoryModel
            ->select('categories.*, COUNT(animals.id) as animal_count')
            ->join('animals', 'animals.category_id = categories.id', 'left')
            ->groupBy('categories.id')
            ->orderBy('categories.created_at', 'DESC')
            ->asArray()
            ->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $categories]);
    }

    public function createCategory()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status') ?: 'active'
        ];

        // Handle file upload if provided
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads', $imageName);
            $data['image'] = $imageName;
        }

        if ($this->categoryModel->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Category created successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to create category']);
        }
    }

    public function updateCategory($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return $this->response->setJSON(['success' => false, 'message' => 'Category not found']);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status')
        ];

        // Handle file upload if provided
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads', $imageName);
            $data['image'] = $imageName;

            // Delete old image if exists
            if (!empty($category['image']) && file_exists(ROOTPATH . 'public/uploads/' . $category['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $category['image']);
            }
        }

        if ($this->categoryModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Category updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update category']);
        }
    }

    public function deleteCategory($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return $this->response->setJSON(['success' => false, 'message' => 'Category not found']);
        }

        // Check if category has animals
        $animals = $this->animalModel->where('category_id', $id)->findAll();
        if (!empty($animals)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot delete category that has animals']);
        }

        if ($this->categoryModel->delete($id)) {
            // Delete image file if exists
            if (!empty($category['image']) && file_exists(ROOTPATH . 'public/uploads/' . $category['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $category['image']);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Category deleted successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete category']);
        }
    }

    public function toggleCategoryStatus($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return $this->response->setJSON(['success' => false, 'message' => 'Category not found']);
        }

        // Get status from request - try different methods
        $status = $this->request->getVar('status');
        if (empty($status)) {
            $status = $this->request->getPost('status');
        }
        if (empty($status)) {
            // Try to get from raw input
            $rawInput = $this->request->getBody();
            parse_str($rawInput, $data);
            $status = $data['status'] ?? null;
        }
        
        // Debug logging
        log_message('info', 'Toggle category status request - Category ID: ' . $id . ', Status: ' . ($status ?? 'null') . ', Raw input: ' . $this->request->getBody());
        
        if (empty($status) || !in_array($status, ['active', 'inactive'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status: ' . ($status ?? 'empty')]);
        }

        if ($this->categoryModel->update($id, ['status' => $status])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Category status updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update category status']);
        }
    }

    // User Management
    public function getUsers()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $users = $this->userModel
            ->select('id, name, email, role, status')
            ->orderBy('id', 'DESC')
            ->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $users]);
    }

    public function createUser()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $name = trim($this->request->getPost('name'));
        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');

        // Strict validation
        if (empty($name) || strlen($name) < 2) {
            return $this->response->setJSON(['success' => false, 'message' => 'Name is required (min 2 characters)']);
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid email is required']);
        }
        if (empty($password) || strlen($password) < 6) {
            return $this->response->setJSON(['success' => false, 'message' => 'Password is required (min 6 characters)']);
        }
        if (empty($role) || !in_array($role, ['admin', 'staff', 'customer'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid role is required']);
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'status' => 'active'
        ];

        if ($this->userModel->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'User created successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to create user. Email may already exist.']);
        }
    }

    public function updateUser($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Get and validate posted data
        $name = trim($this->request->getPost('name'));
        $email = trim($this->request->getPost('email'));
        $role = $this->request->getPost('role');
        $password = $this->request->getPost('password');

        // Strict validation
        if (empty($name) || strlen($name) < 2) {
            return $this->response->setJSON(['success' => false, 'message' => 'Name is required (min 2 characters)']);
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid email is required']);
        }
        if (empty($role) || !in_array($role, ['admin', 'staff', 'customer'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Valid role is required']);
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'role' => $role
        ];

        // Update password only if provided and valid
        if (!empty($password)) {
            if (strlen($password) < 6) {
                return $this->response->setJSON(['success' => false, 'message' => 'Password must be at least 6 characters']);
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'User updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update user. Email may already exist.']);
        }
    }

    public function deleteUser($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        // Prevent deleting current admin user
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot delete your own account']);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Check if user has orders
        $orders = $this->orderModel->where('user_id', $id)->findAll();
        if (!empty($orders)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot delete user that has orders']);
        }

        if ($this->userModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete user']);
        }
    }

    public function toggleUserStatus($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Prevent deactivating current admin user
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot deactivate your own account']);
        }

        // Get status from request - try different methods
        $status = $this->request->getVar('status');
        if (empty($status)) {
            $status = $this->request->getPost('status');
        }
        if (empty($status)) {
            // Try to get from raw input
            $rawInput = $this->request->getBody();
            parse_str($rawInput, $data);
            $status = $data['status'] ?? null;
        }
        
        // Debug logging
        log_message('info', 'Toggle status request - User ID: ' . $id . ', Status: ' . ($status ?? 'null') . ', Raw input: ' . $this->request->getBody());
        
        if (empty($status) || !in_array($status, ['active', 'inactive'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status: ' . ($status ?? 'empty')]);
        }

        if ($this->userModel->update($id, ['status' => $status])) {
            return $this->response->setJSON(['success' => true, 'message' => 'User status updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update user status']);
        }
    }

    // Order Management
    public function getOrders()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

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
            $items = $orderItemModel->select('order_items.*, animals.name, animals.image, animals.gender, categories.name as category_name')
                                    ->join('animals', 'animals.id = order_items.animal_id')
                                    ->join('categories', 'categories.id = animals.category_id', 'left')
                                    ->where('order_items.order_id', $orderId)
                                    ->asArray()
                                    ->findAll();
            $order['items'] = $items;
            // Create a formatted pet order string
            if (!empty($items)) {
                $petNames = array_map(function($item) {
                    $name = $item['name'] ?? 'Unknown Pet';
                    $qty = $item['quantity'] ?? 1;
                    return $qty > 1 ? $name . ' (x' . $qty . ')' : $name;
                }, $items);
                $order['pet_order'] = implode(', ', $petNames);
            } else {
                $order['pet_order'] = 'N/A';
            }
            // Reset the query builder for next iteration
            $orderItemModel->resetQuery();
        }

        return $this->response->setJSON(['success' => true, 'data' => $orders]);
    }

    public function updateOrderStatus($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $order = $this->orderModel->find($id);
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        // Handle both POST and PUT methods
        $status = $this->request->getPost('status') ?: $this->request->getVar('status');
        $paymentStatus = $this->request->getPost('payment_status') ?: $this->request->getVar('payment_status');
        
        $validStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
        $validPaymentStatuses = ['pending', 'paid'];
        
        if (empty($status)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status is required']);
        }
        
        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status. Must be: ' . implode(', ', $validStatuses)]);
        }
        
        if (!empty($paymentStatus) && !in_array($paymentStatus, $validPaymentStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid payment status. Must be: ' . implode(', ', $validPaymentStatuses)]);
        }

        // Use direct database update to ensure it works
        $db = \Config\Database::connect();
        $updateData = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Add payment status if provided
        if (!empty($paymentStatus)) {
            $updateData['payment_status'] = $paymentStatus;
        }
        
        $updated = $db->table('orders')->where('id', $id)->update($updateData);

        if ($updated) {
            // Get the previous order status to check if this is a status change
            $previousStatus = is_array($order) ? ($order['status'] ?? 'pending') : $order->status;
            
            // Update animal status based on order status
            $orderItems = $db->table('order_items')->where('order_id', $id)->get()->getResultArray();
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
            
            // Send notification to customer about status update
            $this->sendOrderStatusNotification($id, $status);
            return $this->response->setJSON(['success' => true, 'message' => 'Order status updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update order status']);
        }
    }

    /**
     * View order tracking (Admin/Staff)
     */
    public function viewOrderTracking($orderId = null)
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'staff'])) {
            return redirect()->to('auth/login')->with('error', 'Please login to access this page');
        }

        if (!$orderId) {
            return redirect()->to('admin/orders')->with('error', 'Order ID is required');
        }

        $orderModel = new OrderModel();
        $order = $orderModel->getOrderWithItems($orderId);

        if (!$order) {
            return redirect()->to('admin/orders')->with('error', 'Order not found');
        }

        return view('admin/order_tracking', [
            'order' => $order
        ]);
    }

    /**
     * Send notification to customer about order status update
     */
    private function sendOrderStatusNotification($orderId, $newStatus)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);
        
        if (!$order) {
            return;
        }

        $statusMessages = [
            'pending' => 'Your order is being reviewed',
            'confirmed' => 'Your order has been confirmed',
            'processing' => 'Your pet is being prepared',
            'shipped' => 'Your order is ready for pickup/delivery',
            'delivered' => 'Your order has been completed',
            'cancelled' => 'Your order has been cancelled'
        ];

        $message = $statusMessages[$newStatus] ?? 'Your order status has been updated';
        
        $db = \Config\Database::connect();
        $notificationData = [
            'user_id' => $order['user_id'],
            'title' => 'Order Status Update',
            'message' => $message,
            'order_id' => $orderId,
            'is_read' => false,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('notifications')->insert($notificationData);
    }

    public function getSalesData()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $period = $this->request->getGet('period') ?: 'week';
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
                $startDate = date('Y-m-01');
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
        // Use a fresh query builder instance
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
                // Only add if there's actual sales data
                if ($catSales > 0) {
                    $categoryLabels[] = $catName;
                    $categoryData[] = $catSales;
                }
            }
        }

        // Ensure we always return arrays, even if empty
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

    public function getSalesStats()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        // Get period from request
        $period = $this->request->getGet('period') ?: 'month';
        
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
                $startDate = date('Y-m-01');
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

        $totalPayments = $this->orderModel
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
                'average_sale' => $averageSale,
                'total_payments' => (float)($totalPayments['total_amount'] ?? 0)
            ]
        ]);
    }

    public function getCompletedDeliveries()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        // Get period from request
        $period = $this->request->getGet('period') ?: 'month';
        
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
                $startDate = date('Y-m-01');
        }

        // Get completed deliveries (orders with 'delivered' status)
        // For now, let's get all delivered orders regardless of date to test
        $deliveries = $this->orderModel
            ->select('orders.id, orders.order_number, orders.total_amount as amount, orders.created_at as delivery_date, users.name as customer_name, animals.name as animal_name')
            ->join('users', 'users.id = orders.user_id')
            ->join('order_items', 'order_items.order_id = orders.id')
            ->join('animals', 'animals.id = order_items.animal_id')
            ->where('orders.status', 'delivered')
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'deliveries' => $deliveries
        ]);
    }

    public function getPayments()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        // Get payments with order and user details
        $payments = $this->orderModel
            ->select('orders.id, orders.order_number, orders.total_amount, orders.payment_status, orders.payment_method, orders.created_at, users.name as customer_name, users.email as customer_email')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $payments
        ]);
    }

    public function updatePaymentStatus($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $order = $this->orderModel->find($id);
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        // Get status from request
        $status = $this->request->getVar('status');
        if (empty($status)) {
            $status = $this->request->getPost('status');
        }
        if (empty($status)) {
            // Try to get from raw input
            $rawInput = $this->request->getBody();
            parse_str($rawInput, $data);
            $status = $data['status'] ?? null;
        }

        if (empty($status) || !in_array($status, ['pending', 'paid'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status. Must be pending or paid']);
        }

        if ($this->orderModel->update($id, ['payment_status' => $status])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Payment status updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update payment status']);
        }
    }

    // ==================== PENDING ANIMALS APPROVAL ====================
    
    public function getPendingAnimals()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        // Return ALL records (pending, approved, rejected); UI handles filtering
        $animals = $this->pendingAnimalModel->getAllPendingAnimalsWithDetails();
        return $this->response->setJSON(['success' => true, 'data' => $animals]);
    }

    public function approvePendingAnimal($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $adminNotes = $this->request->getPost('admin_notes') ?: $this->request->getVar('admin_notes');
        $adminId = session()->get('user_id');

        $result = $this->pendingAnimalModel->approveAnimal($id, $adminId, $adminNotes);

        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'Animal approved and added to store']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve animal']);
        }
    }

    public function rejectPendingAnimal($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $adminNotes = $this->request->getPost('admin_notes') ?: $this->request->getVar('admin_notes');
        $adminId = session()->get('user_id');

        $result = $this->pendingAnimalModel->rejectAnimal($id, $adminId, $adminNotes);

        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'Animal rejected']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject animal']);
        }
    }

    // ==================== NOTIFICATIONS ====================
    
    public function getNotifications()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        // Get all notifications for this admin
        $notifications = $db->table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();

        // Get unread count
        $unreadCount = $db->table('notifications')
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
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        $updated = $db->table('notifications')
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
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        $updated = $db->table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => date('Y-m-d H:i:s')
            ]);

        return $this->response->setJSON(['success' => true, 'message' => 'All notifications marked as read']);
    }

    // Real-time order status updates
    public function getOrderUpdates()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $db = \Config\Database::connect();
        
        // Get recent orders (last 10)
        $recentOrders = $db->table('orders')
            ->select('orders.*, users.name as customer_name, users.email as customer_email')
            ->join('users', 'users.id = orders.user_id')
            ->orderBy('orders.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        // Get pending orders count
        $pendingCount = $db->table('orders')
            ->where('status', 'pending')
            ->countAllResults();

        // Get today's orders count
        $todayCount = $db->table('orders')
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

    // ==================== DELIVERY CONFIRMATION MANAGEMENT ====================
    
    public function getDeliveryConfirmations()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $deliveryConfirmationModel = new \App\Models\DeliveryConfirmationModel();
            $confirmations = $deliveryConfirmationModel->getDeliveriesWithDetails();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $confirmations
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getDeliveryConfirmations: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error retrieving delivery confirmations: ' . $e->getMessage()
            ]);
        }
    }
    
    public function getPendingDeliveryConfirmations()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $deliveryConfirmationModel = new \App\Models\DeliveryConfirmationModel();
            $confirmations = $deliveryConfirmationModel->getPendingConfirmations();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $confirmations
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getPendingDeliveryConfirmations: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error retrieving pending confirmations: ' . $e->getMessage()
            ]);
        }
    }

    public function getDeliveryConfirmation($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $deliveryConfirmationModel = new \App\Models\DeliveryConfirmationModel();
            $confirmation = $deliveryConfirmationModel->getConfirmationWithDetails($id);

            if (!$confirmation) {
                return $this->response->setJSON(['success' => false, 'message' => 'Delivery confirmation not found']);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $confirmation
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getDeliveryConfirmation: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error retrieving delivery confirmation: ' . $e->getMessage()
            ]);
        }
    }

    public function approveDeliveryConfirmation($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $deliveryConfirmationModel = new \App\Models\DeliveryConfirmationModel();
            $confirmation = $deliveryConfirmationModel->find($id);

            if (!$confirmation) {
                return $this->response->setJSON(['success' => false, 'message' => 'Delivery confirmation not found']);
            }

            $adminNotes = $this->request->getVar('admin_notes') ?? $this->request->getPost('admin_notes') ?? '';

            if ($deliveryConfirmationModel->update($id, [
                'status' => 'confirmed',
                'admin_notes' => $adminNotes,
                'approved_by' => session()->get('user_id'),
                'approved_at' => date('Y-m-d H:i:s')
            ])) {
                // Update order status to delivered
                $orderModel = new \App\Models\OrderModel();
                $order = $orderModel->find($confirmation['order_id']);
                $previousStatus = is_array($order) ? ($order['status'] ?? 'pending') : $order->status;
                
                $orderModel->update($confirmation['order_id'], ['status' => 'delivered']);
                
                // Update animal status from 'reserved' to 'sold' when order is delivered
                if ($previousStatus !== 'delivered') {
                    $db = \Config\Database::connect();
                    $orderItems = $db->table('order_items')->where('order_id', $confirmation['order_id'])->get()->getResultArray();
                    $animalModel = new \App\Models\AnimalModel();
                    foreach ($orderItems as $item) {
                        $updateResult = $animalModel->update($item['animal_id'], ['status' => 'sold']);
                        log_message('info', 'Updating animal ID ' . $item['animal_id'] . ' to sold status from delivery confirmation approval. Result: ' . ($updateResult ? 'success' : 'failed'));
                    }
                }
                
                return $this->response->setJSON(['success' => true, 'message' => 'Delivery confirmation approved successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve delivery confirmation']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in approveDeliveryConfirmation: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error approving delivery confirmation']);
        }
    }

    public function createDeliveryConfirmationFromOrder($orderId)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $orderModel = new \App\Models\OrderModel();
            $deliveryModel = new \App\Models\DeliveryConfirmationModel();
            $db = \Config\Database::connect();

            $order = $orderModel->find($orderId);
            if (!$order) {
                return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
            }

            // Ensure delivered + paid
            $status = is_array($order) ? ($order['status'] ?? '') : $order->status;
            $paymentStatus = is_array($order) ? ($order['payment_status'] ?? '') : $order->payment_status;
            if ($status !== 'delivered' || $paymentStatus !== 'paid') {
                return $this->response->setJSON(['success' => false, 'message' => 'Order must be delivered and paid']);
            }

            // Get first animal from order items
            $item = $db->table('order_items')->where('order_id', $orderId)->get()->getRowArray();
            if (!$item) {
                return $this->response->setJSON(['success' => false, 'message' => 'No items found for order']);
            }

            $data = [
                'order_id' => $orderId,
                'staff_id' => session()->get('user_id'),
                'customer_id' => is_array($order) ? $order['user_id'] : $order->user_id,
                'animal_id' => $item['animal_id'],
                'delivery_notes' => 'Auto-generated by admin upon Delivered + Paid',
                'delivery_address' => '',
                'delivery_date' => date('Y-m-d H:i:s'),
                'payment_amount' => is_array($order) ? ($order['total_amount'] ?? 0) : ($order->total_amount ?? 0),
                'payment_method' => is_array($order) ? ($order['payment_method'] ?? '') : ($order->payment_method ?? ''),
                'status' => 'pending'
            ];

            if ($deliveryModel->insert($data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Delivery confirmation created']);
            }

            return $this->response->setJSON(['success' => false, 'message' => 'Failed to create delivery confirmation']);
        } catch (\Exception $e) {
            log_message('error', 'Error in createDeliveryConfirmationFromOrder: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error']);
        }
    }

    public function rejectDeliveryConfirmation($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        try {
            $deliveryConfirmationModel = new \App\Models\DeliveryConfirmationModel();
            $confirmation = $deliveryConfirmationModel->find($id);

            if (!$confirmation) {
                return $this->response->setJSON(['success' => false, 'message' => 'Delivery confirmation not found']);
            }

            $adminNotes = $this->request->getPost('admin_notes') ?? '';

            if ($deliveryConfirmationModel->update($id, [
                'status' => 'rejected',
                'admin_notes' => $adminNotes,
                'approved_by' => session()->get('user_id'),
                'approved_at' => date('Y-m-d H:i:s')
            ])) {
                return $this->response->setJSON(['success' => true, 'message' => 'Delivery confirmation rejected']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject delivery confirmation']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in rejectDeliveryConfirmation: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error rejecting delivery confirmation']);
        }
    }
}