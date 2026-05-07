<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- 1. Authentication Routes ---
$routes->get('/', 'AuthController::login');
$routes->get('register', 'AuthController::register');
$routes->post('register/store', 'AuthController::storeRegister');
$routes->post('login/auth', 'AuthController::loginAuth');
$routes->get('logout', 'AuthController::logout');

// --- 2. Dashboard Routes ---
$routes->get('dashboard', 'DashboardController::index');

// --- 3. Inventory Management CRUD Routes ---
$routes->get('inventory', 'InventoryController::index');             
$routes->post('inventory/store', 'InventoryController::store');      
$routes->post('inventory/update/(:num)', 'InventoryController::update/$1'); 
$routes->get('inventory/delete/(:num)', 'InventoryController::delete/$1');  

// --- 4. Customer Management CRUD Routes ---
$routes->get('customers', 'CustomerController::index');             
$routes->post('customers/store', 'CustomerController::store');      
$routes->post('customers/update/(:num)', 'CustomerController::update/$1'); 
$routes->get('customers/delete/(:num)', 'CustomerController::delete/$1');  

// --- 5. Utang (Debit) Tracking Routes ---
$routes->get('utang', 'UtangController::index');
$routes->post('utang/store', 'UtangController::store');
$routes->get('utang/update-status/(:num)/(:any)', 'UtangController::updateStatus/$1/$2');
$routes->get('utang/delete/(:num)', 'UtangController::delete/$1');

// --- 6. User Management CRUD Routes ---
// These routes handle the User management logic
$routes->get('users', 'UserController::index');             // View the list of users
$routes->post('users/store', 'UserController::store');      // Save a new user
$routes->post('users/update/(:num)', 'UserController::update/$1'); // Update a user
$routes->get('users/delete/(:num)', 'UserController::delete/$1');  // Delete a user