<?php

namespace App\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class RequestEvent extends Event
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }
}