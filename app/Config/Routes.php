<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('/admin', 'Home::admin', ['filter' => 'role:admin']);
$routes->get('/user', 'Home::user', ['filter' => 'role:user']);
$routes->get('/manager', 'Home::manager', ['filter' => 'role:branch_manager']);
// Feature pages
$routes->get('/purchase-request', 'Home::purchaseRequest');
$routes->get('/purchase-orders', 'Home::purchaseOrders');
$routes->get('/deliveries', 'Home::deliveries');
$routes->get('/inventory', 'Home::inventory');
$routes->get('/inventory-staff', 'Inventory::staff', ['filter' => 'role:inventory_staff,branch_manager']);
$routes->get('/api/inventory', 'Inventory::list', ['filter' => 'role:inventory_staff,branch_manager']);
$routes->post('/api/inventory/update/(:num)', 'Inventory::updateQuantity/$1', ['filter' => 'role:inventory_staff,branch_manager']);
$routes->get('/suppliers', 'Home::suppliers');
$routes->get('/transfer', 'Home::transfer');
$routes->get('/franchise', 'Home::franchise');
$routes->get('/settings', 'Home::settings');
