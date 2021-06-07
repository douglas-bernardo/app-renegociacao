<?php

namespace App\Core;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Framework
{
    private $dispatcher;
    private $matcher;
    private $controllerResolver;
    private $argumentResolver;

    public function __construct(EventDispatcher $dispatcher, UrlMatcherInterface $matcher, ControllerResolverInterface $controllerResolver, ArgumentResolverInterface $argumentResolver)
    {
        $this->dispatcher = $dispatcher;
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    public function handle(Request $request)
    {
        $this->matcher->getContext()->fromRequest($request);

        try {

            if(
                $request->getPathInfo() !== '/sessions' &&
                $request->getPathInfo() !== '/users' &&
                $request->getPathInfo() !== '/import/occurrences' &&
                $request->getPathInfo() !== '/import/projects'
            ){
                $this->dispatcher->dispatch(new RequestEvent($request), 'request');
            };

            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (AccessDeniedHttpException $exception) {
            $response = new Response($exception->getMessage(), 401);
        } catch (ResourceNotFoundException $exception) {
            $response = new Response('Not Found', 404);
        } catch (\Exception $exception) {
            //$response = new Response('An error occurred', 500);
            $response = new Response($exception->getMessage(), 500);
        }

        return $response;
    }
}
