<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');
$routes->get('/page', 'AuthController::template');
$routes->get('/dashboard', 'DashboardController::index');

// Route publique — aucun filtre 
$routes->get('/login', 'AuthController::form');
// Objectifs (CRUD)
$routes->get('/objectif/choisir', 'ObjectifController::choisir');
$routes->post('/objectif/enregistrer', 'ObjectifController::enregistrer');
$routes->post('/objectif/modifier/(:num)', 'ObjectifController::modifier/$1');
$routes->get('/objectif/supprimer/(:num)', 'ObjectifController::supprimer/$1');
$routes->get('/objectif/terminer/(:num)', 'ObjectifController::terminer/$1');


// ======== test connexion ========
$routes->get('/test-db', 'TestDb::index');
