<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

/**
 * Authentication
 */

$routes->add('authentication', new Route(
    '/session',
    ['_controller' => 'App\Controller\SessionController::create'],
    [],[],'',[],['POST']
));


/**
 * Dashboard
 */

$routes->add('dashboard', new Route('/', [
    '_controller' => 'App\Controller\DashboardController::index'
]));


/**
 * Usuarios
 */

$routes->add('users_list', new Route(
    '/users',
    ['_controller' => 'App\Controller\UserController::index'],
    [],[],'',[],['GET']
));

$routes->add('users_create', new Route(
    '/users',
    ['_controller' => 'App\Controller\UserController::create'],
    [],[],'',[],['POST']
));


/**
 * Import
 */

$routes->add('import_ocorrencias', new Route('/import', [
    '_controller' => 'App\Controller\ImportOcorrenciasController::index'
]));


/**
 * Occurrence
 */

$routes->add('ocorrencias', new Route('/ocorrencias', [
    '_controller' => 'App\Controller\OcorrenciaController::index'
]));

$routes->add('ocorrencias_show', new Route('/ocorrencias/{ocorrenciaId}', [
    '_controller' => 'App\Controller\OcorrenciaController::show'
]));

/**
 * Finish Occurrence
 */
$routes->add('FinishOccurrenceDefault', new Route(
    '/ocorrencias/{ocorrenciaId}/finish-default', 
    ['_controller' => 'App\Controller\FinishOccurrenceDefaultController::create'],
    [],[],'',[],['POST']
));

$routes->add('FinishOccurrenceWithMaintainedContract', new Route(
    '/ocorrencias/{ocorrenciaId}/finish-maintained', 
    ['_controller' => 'App\Controller\FinishOccurrenceWithMaintainedContractController::create'],
    [],[],'',[],['POST']
));

$routes->add('FinishOccurrenceWithDowngrade', new Route(
    '/ocorrencias/{ocorrenciaId}/finish-downgrade', 
    ['_controller' => 'App\Controller\FinishOccurrenceWithDowngradeController::create'],
    [],[],'',[],['POST']
));


return $routes;