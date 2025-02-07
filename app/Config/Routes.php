<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'KpiController::index');
$routes->get('dashboard', 'KpiController::index');

