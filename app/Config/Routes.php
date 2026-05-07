<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');
$routes->get('/page', 'AuthController::template');

// Route publique — aucun filtre
$routes->get('/login', 'AuthController::form');

// ======== test connexion ========
$routes->get('/test-db', 'TestDb::index');
