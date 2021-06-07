<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

/**
 * Authentication
 */

$routes->add('authentication', new Route(
    '/sessions',
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
 * Users
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

$routes->add('import-occurrences', new Route('/import/occurrences', [
    '_controller' => 'App\Controller\Imports\ImportOccurrencesController::index'
]));

$routes->add('import-projects', new Route('/import/projects', [
    '_controller' => 'App\Controller\Imports\ImportProductsController::index'
]));


/**
 * Occurrences
 */

$routes->add('occurrences', new Route('/occurrences', [
    '_controller' => 'App\Controller\OccurrenceController::index'
]));

$routes->add('occurrences-show', new Route('/occurrences/{id}',
    ['_controller' => 'App\Controller\OccurrenceController::show'],
    [],[],'',[],['GET']
));

$routes->add('occurrences-search', new Route('/occurrences-search',
    ['_controller' => 'App\Controller\OccurrenceController::search'],
    [],[],'',[],['GET']
));

$routes->add('occurrences-register', new Route('/occurrences/{occurrenceId}/register', [
    '_controller' => 'App\Controller\RegisterNegotiationController::create'
]));

$routes->add('occurrences-close', new Route('/occurrences/{occurrenceId}/close', [
    '_controller' => 'App\Controller\OccurrenceController::closeOccurrence'
]));


/**
 * Domain app
 */
$routes->add('situation', new Route('/domain/situation', [
    '_controller' => 'App\Controller\SituationController::index'
]));

$routes->add('status-occurrence', new Route('/domain/status-occurrence', [
    '_controller' => 'App\Controller\StatusOccurrenceController::index'
]));

$routes->add('reasons', new Route('/domain/reasons', [
    '_controller' => 'App\Controller\Domain\NegotiationClosingReasonsController::index'
]));

$routes->add('request-type', new Route('/domain/request-type', [
    '_controller' => 'App\Controller\RequestTypeController::index'
]));

$routes->add('request-source', new Route('/domain/request-source', [
    '_controller' => 'App\Controller\RequestSourceController::index'
]));

$routes->add('contact-type', new Route('/domain/contact-type', [
    '_controller' => 'App\Controller\ContactTypeController::index'
]));

$routes->add('product', new Route('/domain/product', [
    '_controller' => 'App\Controller\ProductController::index'
]));


/**
 * Negotiations
 */

$routes->add('negotiations-list', new Route(
    '/negotiations',
    ['_controller' => 'App\Controller\NegotiationController::index'],
    [],[],'',[],['GET']
));

$routes->add('negotiations-show', new Route(
    '/negotiations/{id}',
    ['_controller' => 'App\Controller\NegotiationController::show'],
    [],[],'',[],['GET']
));

$routes->add('negotiations-search', new Route('/negotiations-search',
    ['_controller' => 'App\Controller\NegotiationController::search'],
    [],[],'',[],['GET']
));

$routes->add('negotiations-update', new Route(
    '/negotiations/{id}',
    ['_controller' => 'App\Controller\NegotiationController::update'],
    [],[],'',[],['PUT']
));


$routes->add('default-close', new Route(
    '/negotiations/{negotiationId}/default-close',
    ['_controller' => 'App\Controller\DefaultNegotiationCloseController::create'],
    [],[],'',[],['POST']
));

$routes->add('negotiations-retention', new Route(
    '/negotiations/{negotiationId}/retention',
    ['_controller' => 'App\Controller\RetentionContractController::create'],
    [],[],'',[],['POST']
));

$routes->add('downgrade-contract', new Route(
    '/negotiations/{negotiationId}/downgrade-contract',
    ['_controller' => 'App\Controller\DowngradeContractController::create'],
    [],[],'',[],['POST']
));

$routes->add('cancel-contract', new Route(
    '/negotiations/{negotiationId}/cancel-contract',
    ['_controller' => 'App\Controller\CancelContractController::create'],
    [],[],'',[],['POST']
));


/**
 * Reports
 */

$routes->add('amount-received', new Route(
    '/reports/amount-received',
    ['_controller' => 'App\Controller\Reports\AmountReceived::index']
));

$routes->add('monthly-efficiency', new Route(
    '/reports/monthly-efficiency',
    ['_controller' => 'App\Controller\Reports\MonthlyEfficiency::index']
));

$routes->add('monthly-requests', new Route(
    '/reports/monthly-requests',
    ['_controller' => 'App\Controller\Reports\MonthlyRequests::index']
));

$routes->add('monthly-requests-seven-days', new Route(
    '/reports/monthly-requests-seven-days',
    ['_controller' => 'App\Controller\Reports\MonthlyRequestsSevenDays::index']
));

$routes->add('accumulated-profit', new Route(
    '/reports/accumulated-profit',
    ['_controller' => 'App\Controller\Reports\AccumulatedProfit::index']
));

$routes->add('monthly-requests-summary', new Route(
    '/reports/monthly-requests-summary',
    ['_controller' => 'App\Controller\Reports\MonthlyRequestsSummary::index']
));

$routes->add('negotiations-analytic', new Route(
    '/reports/negotiations-analytic',
    ['_controller' => 'App\Controller\Reports\NegotiationsAnalyticController::index']
));

$routes->add('open-percentage', new Route(
    '/reports/open-percentage',
    ['_controller' => 'App\Controller\Reports\OpenPercentage::index']
));

return $routes;