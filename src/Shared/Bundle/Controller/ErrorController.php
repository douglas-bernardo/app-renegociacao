<?php


namespace App\Shared\Bundle\Controller;


use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorController
{
    public function exception(FlattenException $exception): JsonResponse
    {
        $statusCode = $exception->getCode() === 0 ? 400 : $exception->getCode();
        return new JsonResponse([
            'status' => 'error',
            'message' => $exception->getMessage(),
        ], $statusCode);
    }
}