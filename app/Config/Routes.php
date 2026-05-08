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

// Dashboard
$routes->get('/dashboard', 'DashboardController::index');
$routes->get('/suggestion/refresh', 'DashboardController::refreshSuggestion');
$routes->get('/dashboard/export-pdf', 'DashboardController::exportPdf');












// ============================================================================================
// $routes->get('/', 'HomeController::index');

// // Auth
// $routes->get('/login', 'AuthController::login');
// $routes->post('/login', 'AuthController::doLogin');
// $routes->get('/register', 'AuthController::registerStep1');
// $routes->post('/register/step1', 'AuthController::doRegisterStep1');
// $routes->post('/register/step2', 'AuthController::doRegisterStep2');
// $routes->get('/logout', 'AuthController::logout');

// // Dashboard (protégé)
// $routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);

// $routes->get('/page', 'AuthController::template');

// // ======== test connexion ========
// $routes->get('/test-db', 'TestDb::index');
