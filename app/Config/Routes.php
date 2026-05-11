<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Pages publiques
$routes->get('/', 'AuthController::home');
$routes->get('/home', 'AuthController::home');

// Authentification
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::doLogin');
$routes->get('/logout', 'AuthController::logout');

// Inscription 2 étapes
$routes->get('/register', 'AuthController::registerStep1');
$routes->get('/register/step1', 'AuthController::registerStep1');
$routes->post('/register/step1', 'AuthController::doRegisterStep1');
$routes->get('/register/step2', 'AuthController::registerStep2');
$routes->post('/register/step2', 'AuthController::doRegisterStep2');

// Objectif utilisateur
$routes->get('/objectif/choisir', 'ObjectifController::choisir');
$routes->post('/objectif/doChoisir', 'ObjectifController::doChoisir');

// Régimes et Sports
$routes->get('/regimes', 'RegimeController::index');
$routes->get('/regimes/(:num)', 'RegimeController::show/$1');
$routes->get('/sports', 'SportController::index');
$routes->get('/sports/(:num)', 'SportController::show/$1');

// Dashboard
$routes->get('/dashboard', 'DashboardController::index');
$routes->get('/suggestion/refresh', 'DashboardController::refreshSuggestion');
$routes->get('/dashboard/export-pdf', 'DashboardController::exportPdf');

// ==================== ADMIN ====================
$routes->get('/admin', 'AdminController::index');
$routes->get('/admin/dashboard', 'AdminController::index');

// CRUD Régimes
$routes->group('admin/regimes', function($routes) {
    $routes->get('/', 'AdminRegimeController::index');
    $routes->get('create', 'AdminRegimeController::create');
    $routes->post('store', 'AdminRegimeController::store');
    $routes->get('edit/(:num)', 'AdminRegimeController::edit/$1');
    $routes->post('update/(:num)', 'AdminRegimeController::update/$1');
    $routes->get('delete/(:num)', 'AdminRegimeController::delete/$1');
});

// CRUD Activités
$routes->group('admin/activites', function($routes) {
    $routes->get('/', 'AdminActiviteController::index');
    $routes->get('create', 'AdminActiviteController::create');
    $routes->post('store', 'AdminActiviteController::store');
    $routes->get('edit/(:num)', 'AdminActiviteController::edit/$1');
    $routes->post('update/(:num)', 'AdminActiviteController::update/$1');
    $routes->get('delete/(:num)', 'AdminActiviteController::delete/$1');
});

// CRUD Utilisateurs
$routes->group('admin/users', function($routes) {
    $routes->get('/', 'AdminUserController::index');
    $routes->get('view/(:num)', 'AdminUserController::view/$1');
    $routes->get('edit/(:num)', 'AdminUserController::edit/$1');
    $routes->post('update/(:num)', 'AdminUserController::update/$1');
    $routes->get('delete/(:num)', 'AdminUserController::delete/$1');
    $routes->get('toggle-gold/(:num)', 'AdminUserController::toggleGold/$1');
});

// CRUD Codes promo
$routes->group('admin/codes', function($routes) {
    $routes->get('/', 'AdminCodeController::index');
    $routes->get('create', 'AdminCodeController::create');
    $routes->post('store', 'AdminCodeController::store');
    $routes->get('edit/(:num)', 'AdminCodeController::edit/$1');
    $routes->post('update/(:num)', 'AdminCodeController::update/$1');
    $routes->get('delete/(:num)', 'AdminCodeController::delete/$1');
    $routes->get('toggle/(:num)', 'AdminCodeController::toggle/$1');
});

// CRUD Paramètres
$routes->group('admin/parametres', function($routes) {
    $routes->get('/', 'AdminParametreController::index');
    $routes->post('update/(:num)', 'AdminParametreController::update/$1');
});


// Porte-monnaie
$routes->get('/wallet', 'WalletController::index');
$routes->post('/wallet/appliquer-code', 'WalletController::appliquerCode');
$routes->post('/wallet/acheter-regime', 'WalletController::acheterRegime');
$routes->post('/wallet/ajouter-argent', 'WalletController::ajouterArgent');