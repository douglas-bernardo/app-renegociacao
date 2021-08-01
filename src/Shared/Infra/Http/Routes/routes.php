<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$domainRouter = include_once __DIR__ . '/../../../../Modules/Domain/Infra/Http/Routes/domainRouter.php';
$importsRouter = include_once __DIR__ . '/../../../../Modules/Imports/Infra/Http/Routes/importsRouter.php';
$sessionRouter = include_once __DIR__ . '/../../../../Modules/Users/Infra/Http/Routes/sessions_routes.php';
$userRouter = include_once __DIR__ . '/../../../../Modules/Users/Infra/Http/Routes/users_routes.php';
$occurrenceRouter = include_once __DIR__ . '/../../../../Modules/Occurrences/Infra/Http/Routes/occurrenceRouter.php';
$negotiationRouter = include_once __DIR__ . '/../../../../Modules/Negotiations/Infra/Http/Routes/negotiationRouter.php';
$reportsRouter = include_once __DIR__ . '/../../../../Modules/Reports/Infra/Http/Routes/reportsRouter.php';
$adminReportsRouter = include_once __DIR__ . '/../../../../Modules/Reports/Infra/Http/Routes/adminReportsRouter.php';

$routes = new RouteCollection();

$routes->addCollection($domainRouter);
$routes->addCollection($importsRouter);
$routes->addCollection($sessionRouter);
$routes->addCollection($userRouter);
$routes->addCollection($occurrenceRouter);
$routes->addCollection($negotiationRouter);
$routes->addCollection($reportsRouter);
$routes->addCollection($adminReportsRouter);

/**
 * Root
 */
$routes->add('dashboard', new Route('/', [
    '_controller' => function () {
        return new Response('2021 - API Renegociação Web | Powered By Análise e Controle BPVC');
    }
]));

return $routes;