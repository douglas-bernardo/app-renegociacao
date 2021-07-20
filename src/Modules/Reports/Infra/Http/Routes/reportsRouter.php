<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$reportsRouter = new RouteCollection();
$namespacePrefix = 'App\Modules\Reports\Infra\Http\Controllers\\';

$monthlyEfficiency = new Route(
    '/monthly-efficiency',
    ['_controller' => $namespacePrefix . 'MonthlyEfficiencyController::index']
);

$amountReceived = new Route(
    '/amount-received',
    ['_controller' => $namespacePrefix . 'AmountReceivedController::index']
);

$monthlyRequests = new Route(
    '/monthly-requests',
    ['_controller' => $namespacePrefix . 'MonthlyRequestsController::index']
);

$monthlyRequestsSummary = new Route(
    '/monthly-requests-summary',
    ['_controller' => $namespacePrefix . 'MonthlyRequestsSummaryController::index']
);

$monthlyRequestsSevenDays = new Route(
    '/monthly-requests-seven-days',
    ['_controller' => $namespacePrefix . 'MonthlyRequestsSevenDaysController::index']
);

$accumulatedProfit = new Route(
    '/accumulated-profit',
    ['_controller' => $namespacePrefix . 'AccumulatedProfitController::index']
);

$openPercentage = new Route(
    '/open-percentage',
    ['_controller' => $namespacePrefix . 'OpenPercentageController::index']
);

$reportsRouter->add('monthly-efficiency', $monthlyEfficiency);
$reportsRouter->add('amount-received', $amountReceived);
$reportsRouter->add('monthly-requests', $monthlyRequests);
$reportsRouter->add('monthly-requests-summary', $monthlyRequestsSummary);
$reportsRouter->add('monthly-requests-seven-days', $monthlyRequestsSevenDays);
$reportsRouter->add('accumulated-profit', $accumulatedProfit);
$reportsRouter->add('open-percentage', $openPercentage);

$reportsRouter->addPrefix('/reports');
return $reportsRouter;