<?php

namespace App\Controllers;

use App\Models\AnimalModel;
use App\Models\CategoryModel;
use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class AdminController extends BaseController
{
    protected $animalModel;
    protected $categoryModel;
    protected $userModel;
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->animalModel = new AnimalModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    public function animalsPage()
    {
        return view('admin/animals');
    }

    public function categoriesPage()
    {
        return view('admin/categories');
    }

    public function ordersPage()
    {
        return view('admin/orders');
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
            'completed_orders' => $this->orderModel->where('status', 'delivered')->countAllResults()
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

        // Calculate monthly revenue
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

    // User Management
    public function getUsers()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $users = $this->userModel
            ->select('id, name, email, role')
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
            'role' => $role
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
            ->findAll();

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
        $validStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        if (empty($status)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status is required']);
        }
        
        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status. Must be: ' . implode(', ', $validStatuses)]);
        }

        // Use direct database update to ensure it works
        $db = \Config\Database::connect();
        $updated = $db->table('orders')->where('id', $id)->update([
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Order status updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update order status']);
        }
    }

    public function getSalesData()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $period = $this->request->getGet('period') ?: 'week';
        $labels = [];
        $sales = [];
        $orders = [];

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
                        ->where('status !=', 'cancelled')
                        ->first();
                    
                    $sales[] = (float)($hourSales['total_amount'] ?? 0);
                    
                    $hourOrders = $this->orderModel
                        ->where('DATE(created_at)', date('Y-m-d'))
                        ->where('HOUR(created_at)', $i)
                        ->where('status !=', 'cancelled')
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
                        ->where('status !=', 'cancelled')
                        ->first();
                    
                    $sales[] = (float)($daySales['total_amount'] ?? 0);
                    
                    $dayOrders = $this->orderModel
                        ->where('DATE(created_at)', $date)
                        ->where('status !=', 'cancelled')
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
                        ->where('status !=', 'cancelled')
                        ->first();
                    
                    $sales[] = (float)($weekSales['total_amount'] ?? 0);
                    
                    $weekOrders = $this->orderModel
                        ->where('DATE(created_at) >=', $weekStart)
                        ->where('DATE(created_at) <=', $weekEnd)
                        ->where('status !=', 'cancelled')
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
                        ->where('status !=', 'cancelled')
                        ->first();
                    
                    $sales[] = (float)($monthSales['total_amount'] ?? 0);
                    
                    $monthOrders = $this->orderModel
                        ->where('DATE_FORMAT(created_at, "%Y-%m")', $month)
                        ->where('status !=', 'cancelled')
                        ->countAllResults();
                    
                    $orders[] = $monthOrders;
                }
                break;
        }

        return $this->response->setJSON([
            'success' => true,
            'labels' => $labels,
            'sales' => $sales,
            'orders' => $orders,
            'period' => $period
        ]);
    }

    // ==================== PENDING ANIMALS APPROVAL ====================
    
    public function getPendingAnimals()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $db = \Config\Database::connect();
        $pendingAnimals = $db->table('pending_animals')
            ->select('pending_animals.*, categories.name as category_name, users.name as added_by_name')
            ->join('categories', 'categories.id = pending_animals.category_id')
            ->join('users', 'users.id = pending_animals.added_by')
            ->orderBy('pending_animals.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['success' => true, 'data' => $pendingAnimals]);
    }

    public function approvePendingAnimal($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin access required']);
        }

        $db = \Config\Database::connect();
        $pendingAnimal = $db->table('pending_animals')->where('id', $id)->get()->getRowArray();

        if (!$pendingAnimal) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pending animal not found']);
        }

        // Move to animals table
        $animalData = [
            'name' => $pendingAnimal['name'],
            'category_id' => $pendingAnimal['category_id'],
            'age' => $pendingAnimal['age'],
            'gender' => $pendingAnimal['gender'],
            'price' => $pendingAnimal['price'],
            'description' => $pendingAnimal['description'],
            'image' => $pendingAnimal['image'],
            'status' => 'available',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $inserted = $db->table('animals')->insert($animalData);

        if ($inserted) {
            // Update pending animal status
            $db->table('pending_animals')->where('id', $id)->update([
                'status' => 'approved',
                'reviewed_by' => session()->get('user_id'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

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

        $db = \Config\Database::connect();
        $updated = $db->table('pending_animals')->where('id', $id)->update([
            'status' => 'rejected',
            'admin_notes' => $adminNotes,
            'reviewed_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
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
}