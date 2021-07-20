<?php

use App\Modules\Users\Infra\Http\Controllers\BootController;
use App\Modules\Users\Infra\Http\Controllers\UserController;
use App\Modules\Users\Infra\Http\Controllers\UserRoleController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$userRouter = new RouteCollection();

/**
 * Boot App
 */
$boot = new Route('/users/boot', ['_controller' => [BootController::class, 'create']]);


$usersList = new Route(
    '/users',
    ['_controller' => [UserController::class, 'index']],
    [], [], '', [], ['GET']
);

$userCreate = new Route(
    '/users',
    ['_controller' => [UserController::class, 'create']],
    [], [], '', [], ['POST']
);

$userRoles = new Route(
    '/users/roles',
    ['_controller' => [UserRoleController::class, 'index']],
    [], [], '', [], ['GET']
);

$userRouter->add('boot', $boot);
$userRouter->add('users-list', $usersList);
$userRouter->add('user-create', $userCreate);
$userRouter->add('user-roles', $userRoles);

return $userRouter;