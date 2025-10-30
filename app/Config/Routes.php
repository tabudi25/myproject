<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Public routes (no authentication required)
$routes->get('/', 'EcommerceController::index');
$routes->get('/shop', 'EcommerceController::shop');
$routes->get('/shop/(:num)', 'EcommerceController::shop/$1');
$routes->get('/animal/(:num)', 'EcommerceController::animal/$1');

// Guest-only routes (login/signup pages - redirect if already logged in)
$routes->group('', ['filter' => 'guest'], function($routes) {
    $routes->get('/login', 'Auth::login');
    $routes->get('/signup', 'Auth::signup');
});

// Authentication routes (no filter needed for these)
$routes->post('/loginAuth', 'Auth::loginAuth');
$routes->post('/register', 'Auth::register');
$routes->get('/logout', 'Auth::logout');

// API routes for real-time functionality
$routes->group('api', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard-stats', 'ApiController::dashboardStats');
    $routes->get('/notifications', 'ApiController::notifications');
});

// Notification routes
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/notifications', 'NotificationController::index');
    $routes->post('/notifications/mark-read/(:num)', 'NotificationController::markAsRead/$1');
    $routes->post('/notifications/mark-all-read', 'NotificationController::markAllAsRead');
    $routes->get('/api/notifications/unread-count', 'NotificationController::getUnreadCount');
    $routes->get('/api/notifications/recent', 'NotificationController::getRecent');
});

// Protected routes (require authentication)
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Ecommerce routes (customer)
    $routes->get('/cart', 'EcommerceController::cart');
    $routes->post('/add-to-cart', 'EcommerceController::addToCart');
    $routes->post('/update-cart', 'EcommerceController::updateCart');
    $routes->get('/remove-from-cart/(:num)', 'EcommerceController::removeFromCart/$1');
    $routes->get('/checkout', 'EcommerceController::checkout');
    $routes->post('/process-checkout', 'EcommerceController::processCheckout');
    $routes->get('/order-success/(:num)', 'EcommerceController::orderSuccess/$1');
    $routes->get('/my-orders', 'EcommerceController::myOrders');
    $routes->get('/order/(:num)', 'EcommerceController::orderDetail/$1');
    $routes->get('/order-tracking/(:num)', 'EcommerceController::orderTracking/$1');
    $routes->get('/api/order-status/(:num)', 'EcommerceController::getOrderStatus/$1');
    $routes->post('/order/(:num)/cancel', 'EcommerceController::cancelOrder/$1');
    $routes->get('/profile', 'EcommerceController::profile');
    $routes->post('/profile/update', 'EcommerceController::updateProfile');
    
});

// Admin routes (require admin access)
$routes->group('', ['filter' => 'admin'], function($routes) {
    $routes->get('/fluffy-admin', 'AdminController::index');
    $routes->get('/fluffy-admin/animals', 'AdminController::animalsPage');
    $routes->get('/fluffy-admin/pending-animals', 'AdminController::pendingAnimalsPage');
    $routes->get('/fluffy-admin/categories', 'AdminController::categoriesPage');
    $routes->get('/fluffy-admin/orders', 'AdminController::ordersPage');
    $routes->get('/fluffy-admin/orders/tracking/(:num)', 'AdminController::viewOrderTracking/$1');
    $routes->get('/fluffy-admin/sales-report', 'AdminController::salesReport');
    $routes->get('/fluffy-admin/payments', 'AdminController::paymentsPage');
    $routes->get('/fluffy-admin/users', 'AdminController::usersPage');
    $routes->get('/fluffy-admin/delivery-confirmations', 'DeliveryController::adminIndex');
    $routes->post('/fluffy-admin/delivery-confirmations/update-status', 'DeliveryController::updateStatus');
    $routes->get('/fluffy-admin/api/delivery-confirmations', 'AdminController::getDeliveryConfirmations');
    $routes->get('/fluffy-admin/api/pending-delivery-confirmations', 'AdminController::getPendingDeliveryConfirmations');
    $routes->get('/fluffy-admin/api/delivery-confirmations/(:num)', 'AdminController::getDeliveryConfirmation/$1');
    $routes->put('/fluffy-admin/api/delivery-confirmations/(:num)/approve', 'AdminController::approveDeliveryConfirmation/$1');
    $routes->put('/fluffy-admin/api/delivery-confirmations/(:num)/reject', 'AdminController::rejectDeliveryConfirmation/$1');
    $routes->post('/fluffy-admin/api/orders/(:num)/delivery-confirmation', 'AdminController::createDeliveryConfirmationFromOrder/$1');
    
    // API routes for admin
    $routes->get('/fluffy-admin/api/animals', 'AdminController::getAnimals');
    $routes->post('/fluffy-admin/api/animals', 'AdminController::createAnimal');
    $routes->put('/fluffy-admin/api/animals/(:num)', 'AdminController::updateAnimal/$1');
    $routes->delete('/fluffy-admin/api/animals/(:num)', 'AdminController::deleteAnimal/$1');
    
    $routes->get('/fluffy-admin/api/users', 'AdminController::getUsers');
    $routes->post('/fluffy-admin/api/users', 'AdminController::createUser');
    $routes->put('/fluffy-admin/api/users/(:num)', 'AdminController::updateUser/$1');
    $routes->put('/fluffy-admin/api/users/(:num)/toggle-status', 'AdminController::toggleUserStatus/$1');
    $routes->delete('/fluffy-admin/api/users/(:num)', 'AdminController::deleteUser/$1');
    
    $routes->get('/fluffy-admin/api/categories', 'AdminController::getCategories');
    $routes->post('/fluffy-admin/api/categories', 'AdminController::createCategory');
    $routes->put('/fluffy-admin/api/categories/(:num)', 'AdminController::updateCategory/$1');
    $routes->put('/fluffy-admin/api/categories/(:num)/toggle-status', 'AdminController::toggleCategoryStatus/$1');
    $routes->delete('/fluffy-admin/api/categories/(:num)', 'AdminController::deleteCategory/$1');
    
    $routes->get('/fluffy-admin/api/orders', 'AdminController::getOrders');
    $routes->put('/fluffy-admin/api/orders/(:num)/status', 'AdminController::updateOrderStatus/$1');
    
    $routes->get('/fluffy-admin/api/sales-data', 'AdminController::getSalesData');
    $routes->get('/fluffy-admin/api/sales-stats', 'AdminController::getSalesStats');
    $routes->get('/fluffy-admin/api/completed-deliveries', 'AdminController::getCompletedDeliveries');
    
    $routes->get('/fluffy-admin/api/payments', 'AdminController::getPayments');
    $routes->put('/fluffy-admin/api/payments/(:num)/status', 'AdminController::updatePaymentStatus/$1');
    
    // Admin approval for pending animals
    $routes->get('/fluffy-admin/api/pending-animals', 'AdminController::getPendingAnimals');
    $routes->post('/fluffy-admin/api/pending-animals/(:num)/approve', 'AdminController::approvePendingAnimal/$1');
    $routes->post('/fluffy-admin/api/pending-animals/(:num)/reject', 'AdminController::rejectPendingAnimal/$1');
    
    // Admin notifications
    $routes->get('/fluffy-admin/api/notifications', 'AdminController::getNotifications');
    $routes->put('/fluffy-admin/api/notifications/(:num)/read', 'AdminController::markNotificationRead/$1');
    $routes->put('/fluffy-admin/api/notifications/mark-all-read', 'AdminController::markAllNotificationsRead');
    
    // Real-time order updates
    $routes->get('/fluffy-admin/api/order-updates', 'AdminController::getOrderUpdates');
});

// Staff routes
$routes->group('', ['filter' => 'staff'], function($routes) {
    // Staff dashboard and pages
    $routes->get('/staff-dashboard', 'StaffController::index');
    $routes->get('/staff/animals', 'StaffController::animalsPage');
    $routes->get('/staff/add-animal', 'StaffController::addAnimalPage');
    $routes->get('/staff/inquiries', 'StaffController::inquiriesPage');
    $routes->get('/staff/orders', 'StaffController::ordersPage');
    $routes->get('/staff/orders/tracking/(:num)', 'AdminController::viewOrderTracking/$1');
    $routes->get('/staff/sales-report', 'StaffController::salesReportPage');
    $routes->get('/staff/payments', 'StaffController::paymentsPage');
    
    // Delivery confirmation routes
    $routes->get('/staff/delivery-confirmations', 'DeliveryController::index');
    $routes->get('/staff/delivery-confirmations/create', 'DeliveryController::create');
    $routes->post('/staff/delivery-confirmations/store', 'DeliveryController::store');
    $routes->post('/staff/delivery-confirmations/get-order-details', 'DeliveryController::getOrderDetails');
    
    // Staff API routes
    $routes->get('/staff/api/animals', 'StaffController::getAnimals');
    $routes->put('/staff/api/animals/(:num)', 'StaffController::updateAnimal/$1');
    $routes->post('/staff/api/animals/add', 'StaffController::addAnimalForApproval');
    
    $routes->get('/staff/api/inquiries', 'StaffController::getInquiries');
    $routes->put('/staff/api/inquiries/(:num)/respond', 'StaffController::respondToInquiry/$1');
    
    
    $routes->get('/staff/api/orders', 'StaffController::getOrders');
    $routes->put('/staff/api/orders/(:num)/confirm', 'StaffController::confirmOrder/$1');
    $routes->post('/staff/api/orders/(:num)/update-status', 'StaffController::updateOrderStatus/$1');
    
    $routes->get('/staff/api/sales-report', 'StaffController::getSalesReport');
    $routes->get('/staff/api/payments', 'StaffController::getPayments');
    $routes->put('/staff/api/payments/(:num)/update', 'StaffController::updatePaymentStatus/$1');
    
    // Staff notifications
    $routes->get('/staff/api/notifications', 'StaffController::getNotifications');
    $routes->put('/staff/api/notifications/(:num)/read', 'StaffController::markNotificationRead/$1');
    $routes->put('/staff/api/notifications/mark-all-read', 'StaffController::markAllNotificationsRead');
    
    // Real-time order updates for staff
    $routes->get('/staff/api/order-updates', 'StaffController::getOrderUpdates');
});

// your petshop homepage route
// $routes->get('/petshop', 'Petshop::index'); 






