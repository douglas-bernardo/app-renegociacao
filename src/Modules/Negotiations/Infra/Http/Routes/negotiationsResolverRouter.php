<?php

use App\Modules\Negotiations\Infra\Http\Controllers\CancelContractController;
use App\Modules\Negotiations\Infra\Http\Controllers\DefaultNegotiationCloseController;
use App\Modules\Negotiations\Infra\Http\Controllers\DowngradeContractController;
use App\Modules\Negotiations\Infra\Http\Controllers\RestoreNegotiationController;
use App\Modules\Negotiations\Infra\Http\Controllers\RetentionContractController;
use App\Modules\Negotiations\Infra\Http\Controllers\TransferNegotiationController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$negotiationResolverRouter = new RouteCollection();

$default =  new Route(
    '/{id}/default',
    ['_controller' => [DefaultNegotiationCloseController::class, 'create']],
    [],[],'',[],['POST']
);

$retention = new Route(
    '/{id}/retention',
    ['_controller' => [RetentionContractController::class, 'create']],
    [],[],'',[],['POST']
);

$downgradeContract = new Route(
    '/{id}/downgrade-contract',
    ['_controller' => [DowngradeContractController::class, 'create']],
    [],[],'',[],['POST']
);

$cancelContract = new Route(
    '/{id}/cancel-contract',
    ['_controller' => [CancelContractController::class, 'create']],
    [],[],'',[],['POST']
);

$transferNegotiation = new Route(
    '/{id}/transfer',
    ['_controller' => [TransferNegotiationController::class, 'update']],
    [],[],'',[],['PUT']
);

$restoreNegotiation = new Route(
    '/{id}/restore',
    ['_controller' => [RestoreNegotiationController::class, 'update']],
    [],[],'',[],['PUT']
);

$negotiationResolverRouter->add('default', $default);
$negotiationResolverRouter->add('retention', $retention);
$negotiationResolverRouter->add('downgrade-contract', $downgradeContract);
$negotiationResolverRouter->add('cancel-contract', $cancelContract);
$negotiationResolverRouter->add('transfer', $transferNegotiation);
$negotiationResolverRouter->add('restore', $restoreNegotiation);

$negotiationResolverRouter->addPrefix('/negotiations');
return $negotiationResolverRouter;