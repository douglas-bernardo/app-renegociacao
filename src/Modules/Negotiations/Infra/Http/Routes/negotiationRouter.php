<?php

use App\Modules\Negotiations\Infra\Http\Controllers\NegotiationController;
use App\Modules\Negotiations\Infra\Http\Controllers\NegotiationSearchController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$negotiationResolverRouter = include_once __DIR__ . '/negotiationsResolverRouter.php';

$negotiationRouter = new RouteCollection();

$list = new Route('/negotiations', ['_controller' => [NegotiationController::class, 'index']]);
$show = new Route('/negotiations/{id}', ['_controller' => [NegotiationController::class, 'show']], [], [], '', [], ['GET']);
$update = new Route('/negotiations/{id}', ['_controller' => [NegotiationController::class, 'update']], [], [], '', [], ['PUT']);
$delete = new Route('/negotiations/{id}', ['_controller' => [NegotiationController::class, 'delete']], [], [], '', [], ['DELETE']);
$search = new Route('/negotiations-search', ['_controller' => [NegotiationSearchController::class, 'index']], [], [], '', [], ['GET']);

$negotiationRouter->add('negotiations-list', $list);
$negotiationRouter->add('negotiations-show', $show);
$negotiationRouter->add('negotiations-update', $update);
$negotiationRouter->add('negotiations-delete', $delete);
$negotiationRouter->add('negotiations-search', $search);

$negotiationRouter->addCollection($negotiationResolverRouter);

return $negotiationRouter;
