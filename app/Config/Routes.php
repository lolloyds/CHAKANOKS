<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::dashboard');
$routes->get('/login', 'Home::login');
$routes->get('/dashboard', 'Home::dashboard');
// Feature pages
$routes->get('/purchase-request', 'Home::purchaseRequest');
$routes->get('/purchase-orders', 'Home::purchaseOrders');
$routes->get('/deliveries', 'Home::deliveries');
$routes->get('/inventory', 'Home::inventory');
$routes->get('/suppliers', 'Home::suppliers');
$routes->get('/transfer', 'Home::transfer');
$routes->get('/franchise', 'Home::franchise');
$routes->get('/settings', 'Home::settings');
$routes->get('/logout', 'Home::logout');
