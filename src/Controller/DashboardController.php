<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController
{
    public function index()
    {
        return new JsonResponse([
            'status' => 'success',
            'dashboard' => 'dashboard data info'
        ]);
    }
}