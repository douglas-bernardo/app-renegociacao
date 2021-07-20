<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$sessionsRouter = new RouteCollection();
$prefix = 'App\Modules\Users\Infra\Http\Controllers\\';

/**
 * Authentication
 */
$sessionsRouter->add('authentication', new Route(
    '/sessions',
    ['_controller' => $prefix . 'SessionController::create'],
    [],[],'',[],['POST']
));

return $sessionsRouter;