<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController
{
    public function index()
    {
        return new JsonResponse([
            'dashboard' => 'dashboard data info'
        ]);
    }
}