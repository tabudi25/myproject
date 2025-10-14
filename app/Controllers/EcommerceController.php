<?php

namespace App\Controllers;

use App\Models\AnimalModel;
use App\Models\CategoryModel;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class EcommerceController extends BaseController
{
    protected $animalModel;
    protected $categoryModel;
    protected $cartModel;
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->animalModel = new AnimalModel();
        $this->categoryModel = new CategoryModel();
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    public function index()
    {
        // Get categories with animal counts
        $categories = $this->categoryModel->where('status', 'active')->findAll();
        
        // Get featured animals (available ones)
        $featuredAnimals = $this->animalModel
            ->select('animals.*, categories.name as category_name')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->where('animals.status', 'available')
            ->orderBy('animals.created_at', 'DESC')
            ->limit(8)
            ->findAll();

        // Get new arrivals
        $newArrivals = $this->animalModel
            ->select('animals.*, categories.name as category_name')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->where('animals.status', 'available')
            ->orderBy('animals.created_at', 'DESC')
            ->limit(4)
            ->findAll();

        // Get cart count for logged in users
        $cartCount = 0;
        if (session()->get('isLoggedIn')) {
            $cartCount = $this->cartModel->where('user_id', session()->get('user_id'))->countAllResults();
        }

        $data = [
            'title' => 'Fluffy Planet - Your Pet Paradise',
            'categories' => $categories,
            'featuredAnimals' => $featuredAnimals,
            'newArrivals' => $newArrivals,
            'cartCount' => $cartCount,
            'isLoggedIn' => session()->get('isLoggedIn') ?? false,
            'userName' => session()->get('user_name') ?? '',
            'userRole' => session()->get('role') ?? ''
        ];

        return view('ecommerce/home', $data);
    }

    public function shop($categoryId = null)
    {
        $builder = $this->animalModel
            ->select('animals.*, categories.name as category_name')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->where('animals.status', 'available');

        // Filter by category if provided
        if ($categoryId) {
            $builder->where('animals.category_id', $categoryId);
        }

        // Handle search
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('animals.name', $search)
                ->orLike('animals.breed', $search)
                ->orLike('categories.name', $search)
                ->groupEnd();
        }

        // Handle sorting
        $sort = $this->request->getGet('sort') ?? 'newest';
        switch ($sort) {
            case 'price_low':
                $builder->orderBy('animals.price', 'ASC');
                break;
            case 'price_high':
                $builder->orderBy('animals.price', 'DESC');
                break;
            case 'name':
                $builder->orderBy('animals.name', 'ASC');
                break;
            default:
                $builder->orderBy('animals.created_at', 'DESC');
        }

        // Pagination
        $perPage = 12;
        $animals = $builder->paginate($perPage);
        $pager = $this->animalModel->pager;

        // Get all categories for filter
        $categories = $this->categoryModel->where('status', 'active')->findAll();

        // Get current category info
        $currentCategory = null;
        if ($categoryId) {
            $currentCategory = $this->categoryModel->find($categoryId);
        }

        // Get cart count for logged in users
        $cartCount = 0;
        if (session()->get('isLoggedIn')) {
            $cartCount = $this->cartModel->where('user_id', session()->get('user_id'))->countAllResults();
        }

        $data = [
            'title' => $currentCategory ? $currentCategory['name'] . ' - Shop' : 'Shop All Animals',
            'animals' => $animals,
            'categories' => $categories,
            'currentCategory' => $currentCategory,
            'pager' => $pager,
            'search' => $search,
            'sort' => $sort,
            'cartCount' => $cartCount,
            'isLoggedIn' => session()->get('isLoggedIn') ?? false,
            'userName' => session()->get('user_name') ?? '',
            'userRole' => session()->get('role') ?? ''
        ];

        return view('ecommerce/shop', $data);
    }

    public function animal($id)
    {
        $animal = $this->animalModel
            ->select('animals.*, categories.name as category_name, categories.id as category_id')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->where('animals.id', $id)
            ->first();

        if (!$animal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Animal not found');
        }

        // Get related animals from same category
        $relatedAnimals = $this->animalModel
            ->select('animals.*, categories.name as category_name')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->where('animals.category_id', $animal['category_id'])
            ->where('animals.id !=', $id)
            ->where('animals.status', 'available')
            ->limit(4)
            ->findAll();

        // Check if item is in cart (for logged in users)
        $inCart = false;
        $cartCount = 0;
        if (session()->get('isLoggedIn')) {
            $cartItem = $this->cartModel
                ->where('user_id', session()->get('user_id'))
                ->where('animal_id', $id)
                ->first();
            $inCart = !empty($cartItem);
            
            $cartCount = $this->cartModel->where('user_id', session()->get('user_id'))->countAllResults();
        }

        $data = [
            'title' => $animal['name'] . ' - Animal Details',
            'animal' => $animal,
            'relatedAnimals' => $relatedAnimals,
            'inCart' => $inCart,
            'cartCount' => $cartCount,
            'isLoggedIn' => session()->get('isLoggedIn') ?? false,
            'userName' => session()->get('user_name') ?? '',
            'userRole' => session()->get('role') ?? ''
        ];

        return view('ecommerce/animal_detail', $data);
    }

    public function cart()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('msg', 'Please login to view your cart');
        }

        $cartItems = $this->cartModel
            ->select('cart.*, animals.name, animals.price, animals.image, animals.status, categories.name as category_name')
            ->join('animals', 'animals.id = cart.animal_id')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->where('cart.user_id', session()->get('user_id'))
            ->findAll();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $data = [
            'title' => 'Shopping Cart',
            'cartItems' => $cartItems,
            'total' => $total,
            'cartCount' => count($cartItems),
            'isLoggedIn' => true,
            'userName' => session()->get('user_name'),
            'userRole' => session()->get('role')
        ];

        return view('ecommerce/cart', $data);
    }

    public function addToCart()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
        }

        $animalId = $this->request->getPost('animal_id');
        $quantity = $this->request->getPost('quantity') ?? 1;
        $userId = session()->get('user_id');

        // Check if animal exists and is available
        $animal = $this->animalModel->asArray()->find($animalId);
        if (!$animal || $animal['status'] !== 'available') {
            return $this->response->setJSON(['success' => false, 'message' => 'Animal not available']);
        }

        // Check if already in cart
        $existingItem = $this->cartModel
            ->where('user_id', $userId)
            ->where('animal_id', $animalId)
            ->first();

        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            $this->cartModel->update($existingItem['id'], ['quantity' => $newQuantity]);
        } else {
            // Add new item
            $this->cartModel->save([
                'user_id' => $userId,
                'animal_id' => $animalId,
                'quantity' => $quantity
            ]);
        }

        // Get updated cart count
        $cartCount = $this->cartModel->where('user_id', $userId)->countAllResults();

        return $this->response->setJSON(['success' => true, 'message' => 'Added to cart!', 'cartCount' => $cartCount]);
    }

    public function updateCart()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $cartId = $this->request->getPost('cart_id');
        $quantity = $this->request->getPost('quantity');

        if ($quantity <= 0) {
            $this->cartModel->delete($cartId);
        } else {
            $this->cartModel->update($cartId, ['quantity' => $quantity]);
        }

        return redirect()->to('/cart')->with('msg', 'Cart updated successfully');
    }

    public function removeFromCart($cartId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $this->cartModel->delete($cartId);
        return redirect()->to('/cart')->with('msg', 'Item removed from cart');
    }

    public function checkout()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('msg', 'Please login to checkout');
        }

        $cartItems = $this->cartModel
            ->select('cart.*, animals.name, animals.price, animals.image, animals.status')
            ->join('animals', 'animals.id = cart.animal_id')
            ->where('cart.user_id', session()->get('user_id'))
            ->findAll();

        if (empty($cartItems)) {
            return redirect()->to('/cart')->with('msg', 'Your cart is empty');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            if ($item['status'] !== 'available') {
                return redirect()->to('/cart')->with('msg', 'Some items in your cart are no longer available');
            }
            $subtotal += $item['price'] * $item['quantity'];
        }

        $deliveryFee = 100; // Default delivery fee

        $data = [
            'title' => 'Checkout',
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'deliveryFee' => $deliveryFee,
            'total' => $subtotal + $deliveryFee,
            'cartCount' => count($cartItems),
            'isLoggedIn' => true,
            'userName' => session()->get('user_name'),
            'userRole' => session()->get('role')
        ];

        return view('ecommerce/checkout', $data);
    }

    public function processCheckout()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        // Debug: Check if user_id exists
        if (!$userId) {
            return redirect()->to('/login')->with('msg', 'Session expired. Please login again.');
        }
        
        // Get cart items
        $cartItems = $this->cartModel
            ->select('cart.*, animals.name, animals.price, animals.status')
            ->join('animals', 'animals.id = cart.animal_id')
            ->where('cart.user_id', $userId)
            ->findAll();

        if (empty($cartItems)) {
            return redirect()->to('/cart')->with('msg', 'Your cart is empty');
        }

        // Validate form data
        $deliveryType = $this->request->getPost('delivery_type');
        $paymentMethod = $this->request->getPost('payment_method');
        $deliveryAddress = $this->request->getPost('delivery_address');

        // Basic validation
        if (empty($deliveryType)) {
            return redirect()->back()->with('msg', 'Please select a delivery type');
        }

        if (empty($paymentMethod)) {
            return redirect()->back()->with('msg', 'Please select a payment method');
        }

        if ($deliveryType === 'delivery' && empty($deliveryAddress)) {
            return redirect()->back()->with('msg', 'Delivery address is required for home delivery');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            if ($item['status'] !== 'available') {
                return redirect()->to('/cart')->with('msg', 'Some items are no longer available');
            }
            $subtotal += $item['price'] * $item['quantity'];
        }

        $deliveryFee = ($deliveryType === 'delivery') ? 100 : 0;
        $total = $subtotal + $deliveryFee;

        // Generate order number
        $orderNumber = 'FP' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Create order
        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => $userId,
            'total_amount' => $total,
            'status' => 'pending',
            'delivery_type' => $deliveryType,
            'delivery_address' => $deliveryAddress,
            'delivery_fee' => $deliveryFee,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'notes' => $this->request->getPost('notes') ?: null
        ];

        // Use direct database insert to bypass model issues
        $db = \Config\Database::connect();
        $builder = $db->table('orders');
        $result = $builder->insert($orderData);
        $orderId = $result ? $db->insertID() : false;

        if ($orderId) {
            // Create order items using direct database insert
            $orderItemsBuilder = $db->table('order_items');
            foreach ($cartItems as $item) {
                $orderItemsBuilder->insert([
                    'order_id' => $orderId,
                    'animal_id' => $item['animal_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Update animal status to sold if quantity is 1 (assuming each animal is unique)
                if ($item['quantity'] == 1) {
                    $this->animalModel->update($item['animal_id'], ['status' => 'sold']);
                }
            }

            // Clear cart
            $this->cartModel->where('user_id', $userId)->delete();

            return redirect()->to('/order-success/' . $orderId)->with('msg', 'Order placed successfully!');
        } else {
            return redirect()->back()->with('msg', 'Failed to place order. Please try again.');
        }
    }

    public function orderSuccess($orderId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $order = $this->orderModel
            ->where('id', $orderId)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$order) {
            return redirect()->to('/')->with('msg', 'Order not found');
        }

        $data = [
            'title' => 'Order Success',
            'order' => $order,
            'cartCount' => 0,
            'isLoggedIn' => true,
            'userName' => session()->get('user_name'),
            'userRole' => session()->get('role')
        ];

        return view('ecommerce/order_success', $data);
    }

    public function myOrders()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $orders = $this->orderModel
            ->where('user_id', session()->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'My Orders',
            'orders' => $orders,
            'cartCount' => $this->cartModel->where('user_id', session()->get('user_id'))->countAllResults(),
            'isLoggedIn' => true,
            'userName' => session()->get('user_name'),
            'userRole' => session()->get('role')
        ];

        return view('ecommerce/my_orders', $data);
    }

    public function orderDetail($orderId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $order = $this->orderModel
            ->where('id', $orderId)
            ->where('user_id', session()->get('user_id'))
            ->first();

        if (!$order) {
            return redirect()->to('/my-orders')->with('msg', 'Order not found');
        }

        $orderItems = $this->orderItemModel
            ->select('order_items.*, animals.name, animals.image, animals.gender, categories.name as category_name')
            ->join('animals', 'animals.id = order_items.animal_id')
            ->join('categories', 'categories.id = animals.category_id', 'left')
            ->where('order_items.order_id', $orderId)
            ->findAll();

        $data = [
            'title' => 'Order #' . $order['order_number'],
            'order' => $order,
            'orderItems' => $orderItems,
            'cartCount' => $this->cartModel->where('user_id', session()->get('user_id'))->countAllResults(),
            'isLoggedIn' => true,
            'userName' => session()->get('user_name'),
            'userRole' => session()->get('role')
        ];

        return view('ecommerce/order_detail', $data);
    }

    public function cancelOrder($orderId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
        }

        $userId = session()->get('user_id');
        
        // Get the order and verify ownership
        $order = $this->orderModel
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        // Check if order can be cancelled (only pending orders)
        if ($order['status'] !== 'pending') {
            return $this->response->setJSON(['success' => false, 'message' => 'Order cannot be cancelled at this stage']);
        }

        // Update order status to cancelled using direct DB update
        $db = \Config\Database::connect();
        $updated = $db->table('orders')->where('id', $orderId)->update(['status' => 'cancelled', 'updated_at' => date('Y-m-d H:i:s')]);

        if ($updated) {
            // Get order items to restore animal availability
            $orderItems = $this->orderItemModel->where('order_id', $orderId)->findAll();
            
            // Restore animal availability using direct DB update
            foreach ($orderItems as $item) {
                $db->table('animals')->where('id', $item['animal_id'])->update(['status' => 'available']);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Order cancelled successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to cancel order']);
        }
    }

    public function profile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('msg', 'Please login to view your profile');
        }

        $userId = session()->get('user_id');
        $userModel = new \App\Models\UserModel();
        $user = $userModel->asArray()->find($userId);

        if (!$user) {
            return redirect()->to('/')->with('msg', 'User not found');
        }

        // Get user statistics
        $totalOrders = $this->orderModel->where('user_id', $userId)->countAllResults();
        $completedOrders = $this->orderModel->where('user_id', $userId)->where('status', 'delivered')->countAllResults();
        $totalSpent = $this->orderModel
            ->selectSum('total_amount')
            ->where('user_id', $userId)
            ->where('status', 'delivered')
            ->first();

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'totalSpent' => $totalSpent['total_amount'] ?? 0,
            'cartCount' => $this->cartModel->where('user_id', $userId)->countAllResults(),
            'isLoggedIn' => true,
            'userName' => session()->get('user_name'),
            'userRole' => session()->get('role')
        ];

        return view('ecommerce/profile', $data);
    }

    public function updateProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $userModel = new \App\Models\UserModel();

        // Validate input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[2]|max_length[255]',
            'email' => "required|valid_email|is_unique[users.email,id,$userId]"
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('msg', 'Validation failed: ' . implode(', ', $validation->getErrors()));
        }

        $data = [
            'name' => trim($this->request->getPost('name')),
            'email' => trim($this->request->getPost('email'))
        ];

        // Update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 6) {
                return redirect()->back()->withInput()->with('msg', 'Password must be at least 6 characters long');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($userModel->update($userId, $data)) {
            // Update session data
            session()->set([
                'user_name' => $data['name'],
                'email' => $data['email']
            ]);

            return redirect()->to('/profile')->with('msg', 'Profile updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('msg', 'Failed to update profile. Please try again.');
        }
    }
}