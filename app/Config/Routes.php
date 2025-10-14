<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Public routes (no authentication required)
// $routes->get('/', 'FluffyController::index');

// Guest-only routes (login/signup pages - redirect if already logged in)
$routes->group('', ['filter' => 'guest'], function($routes) {
    $routes->get('/login', 'Auth::login');
    $routes->get('/signup', 'Auth::signup');
});

// Authentication routes (no filter needed for these)
$routes->post('/loginAuth', 'Auth::loginAuth');
$routes->post('/register', 'Auth::register');
$routes->get('/logout', 'Auth::logout');

// Protected routes (require authentication)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/cat', 'FluffyController::cat');
    $routes->get('/dog', 'FluffyController::dog');
    $routes->get('/rabbit', 'FluffyController::rabbit');
    $routes->get('/hamster', 'FluffyController::hamster');
    $routes->get('/fish', 'FluffyController::fish');
    $routes->get('/categories', 'FluffyController::categories');
    $routes->get('/newarrival', 'FluffyController::newarrival');
    $routes->get('/order', 'FluffyController::order');
    $routes->get('/order_transactions', 'FluffyController::order_transactions');
    $routes->get('/history', 'FluffyController::history');
    $routes->get('/petshop', 'FluffyController::petshop');
    $routes->post('/order', 'FluffyController::save_order'); 
    $routes->post('confirm_order/(:num)', 'FluffyController::confirm_order/$1');
    $routes->get('view_order/(:num)', 'Order::view_order/$1');
    
    // Student routes (if these should be protected)
    $routes->get('/student', 'Student::index');
    $routes->get('/student/create', 'Student::create');
    $routes->post('/student/store', 'Student::store');
    $routes->get('/student/delete/(:num)', 'Student::delete/$1');
    
    // Admin routes (admin access only)
    $routes->get('/fluffy-admin', 'AdminController::index');
    $routes->get('/admin/animals', 'AdminController::getAnimals');
    $routes->post('/admin/animals', 'AdminController::createAnimal');
    $routes->put('/admin/animals/(:num)', 'AdminController::updateAnimal/$1');
    $routes->delete('/admin/animals/(:num)', 'AdminController::deleteAnimal/$1');
});

// your petshop homepage route
// $routes->get('/petshop', 'Petshop::index'); 






