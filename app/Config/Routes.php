<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route publique — aucun filtre
$routes->get('/', 'LoginController::form');

// ======== test connexion ========
$routes->get('/test-db', 'TestDb::index');
