<?php

use App\Modules\Domain\Infra\Http\Controllers\ContactTypeController;
use App\Modules\Domain\Infra\Http\Controllers\PermissionsController;
use App\Modules\Domain\Infra\Http\Controllers\ProductController;
use App\Modules\Domain\Infra\Http\Controllers\ReasonsController;
use App\Modules\Domain\Infra\Http\Controllers\RequestSourceController;
use App\Modules\Domain\Infra\Http\Controllers\RequestTypeController;
use App\Modules\Domain\Infra\Http\Controllers\RolesController;
use App\Modules\Domain\Infra\Http\Controllers\SituationController;
use App\Modules\Domain\Infra\Http\Controllers\StatusOccurrenceController;
use App\Modules\Domain\Infra\Http\Controllers\TransferReasonsController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$domainRouter = new RouteCollection();

/**
 * Entities Domain routes
 */
$situation = new Route('/situation', ['_controller' => [SituationController::class, 'index']]);
$statusOccurrence = new Route('/status-occurrence', ['_controller' => [StatusOccurrenceController::class, 'index']]);
$reasons = new Route('/reasons', ['_controller' => [ReasonsController::class, 'index']]);
$requestType = new Route('/request-type', ['_controller' => [RequestTypeController::class, 'index']]);
$requestSource = new Route('/request-source', ['_controller' => [RequestSourceController::class, 'index']]);
$contactType = new Route('/contact-type', ['_controller' => [ContactTypeController::class, 'index']]);
$product = new Route('/product', ['_controller' => [ProductController::class, 'index']]);
$transferReasons = new Route('/transfer-reasons', ['_controller' => [TransferReasonsController::class, 'index']]);

$domainRouter->add('situation', $situation);
$domainRouter->add('status-occurrence', $statusOccurrence);
$domainRouter->add('reasons', $reasons);
$domainRouter->add('request-type', $requestType);
$domainRouter->add('request-source', $requestSource);
$domainRouter->add('contact-type', $contactType);
$domainRouter->add('product', $product);
$domainRouter->add('transfer-reasons', $transferReasons);


/**
 * Authorization settings routes
 */
$permissionsList = new Route(
    '/permission',
    ['_controller' => [PermissionsController::class, 'index']],
    [], [], '', [], ['GET']
);

$permissionCreate = new Route(
    '/permission',
    ['_controller' => [PermissionsController::class, 'create']],
    [], [], '', [], ['POST']
);

$roleList = new Route(
    '/role',
    ['_controller' => [RolesController::class, 'index']],
    [], [], '', [], ['GET']
);

$roleCreate = new Route(
    '/role',
    ['_controller' => [RolesController::class, 'create']],
    [], [], '', [], ['POST']
);

$roleUpdate = new Route(
    '/role/{id}',
    ['_controller' => [RolesController::class, 'update']],
    [], [], '', [], ['PUT']
);

$roleShow = new Route(
    '/role/{id}',
    ['_controller' => [RolesController::class, 'show']],
    [], [], '', [], ['GET']
);

$domainRouter->add('permissions-list', $permissionsList);
$domainRouter->add('permission-create', $permissionCreate);
$domainRouter->add('role-list', $roleList);
$domainRouter->add('role-create', $roleCreate);
$domainRouter->add('role-update', $roleUpdate);
$domainRouter->add('role-show', $roleShow);

$domainRouter->addPrefix('/domain');
return $domainRouter;