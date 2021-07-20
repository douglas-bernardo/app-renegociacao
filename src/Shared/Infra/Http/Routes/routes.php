<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$domainRouter = include_once __DIR__ . '/../../../../Modules/Domain/Infra/Http/Routes/domainRouter.php';
$importsRouter = include_once __DIR__ . '/../../../../Modules/Imports/Infra/Http/Routes/importsRouter.php';
$sessionRouter = include_once __DIR__ . '/../../../../Modules/Users/Infra/Http/Routes/sessions_routes.php';
$userRouter = include_once __DIR__ . '/../../../../Modules/Users/Infra/Http/Routes/users_routes.php';
$occurrenceRouter = include_once __DIR__ . '/../../../../Modules/Occurrences/Infra/Http/Routes/occurrenceRouter.php';
$negotiationRouter = include_once __DIR__ . '/../../../../Modules/Negotiations/Infra/Http/Routes/negotiationRouter.php';
$reportsRouter = include_once __DIR__ . '/../../../../Modules/Reports/Infra/Http/Routes/reportsRouter.php';

$routes = new RouteCollection();

$routes->addCollection($domainRouter);
$routes->addCollection($importsRouter);

$routes->addCollection($sessionRouter);
$routes->addCollection($userRouter);
$routes->addCollection($occurrenceRouter);
$routes->addCollection($negotiationRouter);
$routes->addCollection($reportsRouter);

/**
 * Dashboard / Home
 */
$routes->add('dashboard', new Route('/', [
    '_controller' => 'App\Controller\DashboardController::index'
]));

return $routes;