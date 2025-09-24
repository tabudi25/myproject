<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'FluffyController::index');
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




