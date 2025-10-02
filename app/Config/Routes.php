<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route -> login page
$routes->get('/', 'Auth::login');

// ==================== AUTHENTICATION ====================
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');


// ==================== DASHBOARD ====================
$routes->get('dashboard', 'Auth::dashboard');

// ==================== FEATURE PAGES ====================
$routes->get('purchase-request', 'Home::purchaseRequest');
$routes->get('purchase-orders', 'Home::purchaseOrders');
$routes->get('deliveries', 'Home::deliveries');
$routes->get('inventory', 'Inventory::inventory');
$routes->get('suppliers', 'Home::suppliers');
$routes->get('transfer', 'Home::transfer');
$routes->get('franchise', 'Home::franchise');
$routes->get('settings', 'Home::settings');

// ==================== INVENTORY MANAGEMENT ====================

$routes->post('inventory/receive', 'Inventory::receiveItem', ['filter' => 'role:Inventory Staff,Branch Manager']);
$routes->post('inventory/use', 'Inventory::useItem', ['filter' => 'role:Inventory Staff,Branch Manager']);
$routes->post('inventory/transfer', 'Inventory::transferItem', ['filter' => 'role:Inventory Staff,Branch Manager']);
$routes->post('inventory/adjust', 'Inventory::adjustItem', ['filter' => 'role:Inventory Staff,Branch Manager']);
$routes->post('inventory/spoil', 'Inventory::spoilItem', ['filter' => 'role:Inventory Staff,Branch Manager']);


// Branch inventory routes
$routes->post('inventory/useItem', 'Inventory::useItem');
$routes->post('inventory/reportDamage', 'Inventory::reportDamage');

// Central office routes
$routes->post('inventory/transferItem', 'Inventory::transferItem');
$routes->post('inventory/adjustItem', 'Inventory::adjustItem');
