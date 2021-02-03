<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\Core\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$dotenv = Dotenv\Dotenv::createImmutable( dirname(__DIR__ . '../') );
$dotenv->load();

$request = Request::createFromGlobals();
$routes = include __DIR__ . '/../src/Routes/routes.php';

$response = new Response();

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new \App\Core\AuthenticateSubscriber());

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);

$response = $framework->handle($request);
$response->send();