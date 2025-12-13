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
$routes->get('dashboard/deliveries-summary', 'Home::deliveriesSummary');

// ==================== FEATURE PAGES ====================
$routes->get('purchase-request', 'PurchaseRequest::index');
$routes->get('purchase-orders', 'PurchaseOrder::index');
$routes->get('deliveries', 'Inventory::deliveries');
$routes->get('inventory', 'Inventory::inventory');
$routes->get('suppliers', 'Suppliers::index');
$routes->get('transfer', 'Home::transfer');
$routes->get('franchise', 'Home::franchise');
$routes->get('settings', 'Home::settings');
$routes->post('settings/update-profile', 'Home::updateProfile');
$routes->post('settings/update-password', 'Home::updatePassword');
$routes->post('settings/update-notifications', 'Home::updateNotifications');

// ==================== INVENTORY MANAGEMENT ====================

$routes->post('inventory/receive', 'Inventory::receiveItem', ['filter' => 'role:Inventory Staff,Branch Manager']);
$routes->post('inventory/use', 'Inventory::useItem', ['filter' => 'role:Inventory Staff,Branch Manager']);
$routes->post('inventory/useItem', 'Inventory::useItem');
$routes->post('inventory/transfer', 'Inventory::transferItem', ['filter' => 'role:Inventory Staff,Branch Manager']);
$routes->post('inventory/transferItem', 'Inventory::transferItem');
$routes->post('inventory/adjust', 'Inventory::adjustItem', ['filter' => 'role:Inventory Staff,Branch Manager']);
$routes->post('inventory/adjustItem', 'Inventory::adjustItem');
$routes->post('inventory/spoil', 'Inventory::reportDamage', ['filter' => 'role:Inventory Staff,Branch Manager']);

// Delivery routes
$routes->post('deliveries/approve', 'Inventory::approveDelivery');
$routes->get('deliveries/create', 'Deliveries::create');
$routes->post('deliveries/create', 'Deliveries::create');
$routes->get('deliveries/success', 'Deliveries::success');
$routes->get('deliveries/update-status/(:num)', 'Deliveries::updateStatus/$1');
$routes->post('deliveries/update-status/(:num)', 'Deliveries::updateStatus/$1');
$routes->get('deliveries/status-updated', 'Deliveries::statusUpdated');
$routes->get('deliveries/mark-delivered/(:num)', 'Deliveries::markDelivered/$1');
$routes->get('deliveries/delivered-success', 'Deliveries::deliveredSuccess');

// Alert routes
$routes->get('inventory/alerts', 'Inventory::getAlerts');
$routes->post('inventory/alerts/acknowledge', 'Inventory::acknowledgeAlert');
$routes->post('inventory/alerts/check', 'Inventory::checkAlerts');
$routes->get('inventory/edit/(:num)', 'Inventory::edit/$1');
$routes->post('inventory/edit/(:num)', 'Inventory::edit/$1');
$routes->get('inventory/remove/(:num)', 'Inventory::remove/$1');

// ==================== PURCHASE REQUEST ====================
$routes->post('purchase-request/create', 'PurchaseRequest::create');
$routes->post('purchase-request/approve/(:num)', 'PurchaseRequest::approve/$1');
$routes->post('purchase-request/reject/(:num)', 'PurchaseRequest::reject/$1');
$routes->get('purchase-request/get/(:num)', 'PurchaseRequest::get/$1');

// ==================== PURCHASE ORDERS ====================
$routes->post('purchase-orders/create', 'PurchaseOrder::create');
$routes->post('purchase-orders/update-status/(:num)', 'PurchaseOrder::updateStatus/$1');
$routes->post('purchase-orders/schedule-delivery/(:num)', 'PurchaseOrder::scheduleDelivery/$1');
$routes->post('purchase-orders/update-delivery-timeline/(:num)', 'PurchaseOrder::updateDeliveryTimeline/$1');
$routes->post('purchase-orders/receive-delivery/(:num)', 'PurchaseOrder::receiveDelivery/$1');
$routes->post('purchase-orders/confirm-delivery/(:num)', 'PurchaseOrder::confirmDelivery/$1');
$routes->get('purchase-orders/get/(:num)', 'PurchaseOrder::get/$1');

// ==================== SUPPLIERS ====================
$routes->get('suppliers/edit/(:num)', 'Suppliers::edit/$1');
$routes->post('suppliers/update/(:num)', 'Suppliers::update/$1');
$routes->get('suppliers/delete/(:num)', 'Suppliers::delete/$1');
