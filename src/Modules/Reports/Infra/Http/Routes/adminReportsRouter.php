<?php


use App\Modules\Reports\Infra\Http\Controllers\AccumulatedEfficiencyController;
use App\Modules\Reports\Infra\Http\Controllers\AccumulatedEfficiencySevenDaysController;
use App\Modules\Reports\Infra\Http\Controllers\AccumulatedRetentionEfficiencyController;
use App\Modules\Reports\Infra\Http\Controllers\OpenPercentageAdminController;
use App\Modules\Reports\Infra\Http\Controllers\RetentionDowngradeBalanceController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$adminReportsRouter = new RouteCollection();

$accumulatedEfficiency = new Route(
    '/accumulated-efficiency',
    ['_controller' => [AccumulatedEfficiencyController::class, 'index']]
);

$accumulatedEfficiencySevenDays = new Route(
    '/accumulated-efficiency-seven-days',
    ['_controller' => [AccumulatedEfficiencySevenDaysController::class, 'index']]
);

$openPercentageAdmin = new Route(
    '/open-percentage',
    ['_controller' => [OpenPercentageAdminController::class, 'index']]
);

$retentionDowngradeBalance = new Route(
    '/retention-downgrade-balance',
    ['_controller' => [RetentionDowngradeBalanceController::class, 'index']]
);

$accumulatedRetentionEfficiency = new Route(
    '/accumulated-retention-efficiency',
    ['_controller' => [AccumulatedRetentionEfficiencyController::class, 'index']]
);

$adminReportsRouter->add('accumulated-efficiency', $accumulatedEfficiency);
$adminReportsRouter->add('accumulated-efficiency-seven-days', $accumulatedEfficiencySevenDays);
$adminReportsRouter->add('open-percentage-admin', $openPercentageAdmin);
$adminReportsRouter->add('retention-downgrade-balance', $retentionDowngradeBalance);
$adminReportsRouter->add('accumulated-retention-efficiency', $accumulatedRetentionEfficiency);
$adminReportsRouter->addPrefix('/reports/admin');

return $adminReportsRouter;