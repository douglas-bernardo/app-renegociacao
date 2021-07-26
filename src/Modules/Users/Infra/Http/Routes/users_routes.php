<?php

use App\Modules\Users\Infra\Http\Controllers\BootController;
use App\Modules\Users\Infra\Http\Controllers\CreateNewPasswordController;
use App\Modules\Users\Infra\Http\Controllers\ResetPasswordController;
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

$userShow = new Route(
    '/users/{id}',
    ['_controller' => [UserController::class, 'show']],
    [], [], '', [], ['GET']
);

$userUpdate = new Route(
    '/users/{id}',
    ['_controller' => [UserController::class, 'update']],
    [], [], '', [], ['PUT']
);

$createNewPassword = new Route(
    '/users/{id}/create-password',
    ['_controller' => [CreateNewPasswordController::class, 'update']],
    [], [], '', [], ['PUT']
);

$resetPassword = new Route(
    '/users/{id}/reset-password',
    ['_controller' => [ResetPasswordController::class, 'update']],
    [], [], '', [], ['PUT']
);

$userRoles = new Route(
    '/users/{id}/roles',
    ['_controller' => [UserRoleController::class, 'index']],
    [], [], '', [], ['GET']
);

$userRouter->add('boot', $boot);
$userRouter->add('users-list', $usersList);
$userRouter->add('user-create', $userCreate);
$userRouter->add('user-show', $userShow);
$userRouter->add('user-update', $userUpdate);
$userRouter->add('create-password', $createNewPassword);
$userRouter->add('reset-password', $resetPassword);
$userRouter->add('user-roles', $userRoles);

return $userRouter;