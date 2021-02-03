<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NegociacaoController
{
    public function create(Request $request)
    {
        $data = $request->toArray();
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        
        return new JsonResponse([
            'data' => $data
        ]);
    }
}
