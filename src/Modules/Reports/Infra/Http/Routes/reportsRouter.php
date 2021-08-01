<?php


use App\Modules\Reports\Infra\Http\Controllers\AccumulatedProfitController;
use App\Modules\Reports\Infra\Http\Controllers\AmountReceivedController;
use App\Modules\Reports\Infra\Http\Controllers\MonthlyEfficiencyController;
use App\Modules\Reports\Infra\Http\Controllers\MonthlyRequestsController;
use App\Modules\Reports\Infra\Http\Controllers\MonthlyRequestsSevenDaysController;
use App\Modules\Reports\Infra\Http\Controllers\MonthlyRequestsSummaryController;
use App\Modules\Reports\Infra\Http\Controllers\OpenPercentageController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$reportsRouter = new RouteCollection();

$monthlyEfficiency = new Route(
    '/monthly-efficiency',
    ['_controller' => [MonthlyEfficiencyController::class, 'index']]
);

$amountReceived = new Route(
    '/amount-received',
    ['_controller' => [AmountReceivedController::class, 'index']]
);

$monthlyRequests = new Route(
    '/monthly-requests',
    ['_controller' => [MonthlyRequestsController::class, 'index']]
);

$monthlyRequestsSummary = new Route(
    '/monthly-requests-summary',
    ['_controller' => [MonthlyRequestsSummaryController::class, 'index']]
);

$monthlyRequestsSevenDays = new Route(
    '/monthly-requests-seven-days',
    ['_controller' => [MonthlyRequestsSevenDaysController::class, 'index']]
);

$accumulatedProfit = new Route(
    '/accumulated-profit',
    ['_controller' => [AccumulatedProfitController::class, 'index']]
);

$openPercentage = new Route(
    '/open-percentage',
    ['_controller' => [OpenPercentageController::class, 'index']]
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