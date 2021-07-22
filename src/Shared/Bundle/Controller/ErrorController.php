<?php


namespace App\Shared\Bundle\Controller;


use App\Shared\Infra\Database\Transaction;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorController
{
    public function exception(FlattenException $exception): JsonResponse
    {
        $statusCode = $exception->getCode() === 0 ? 400 : $exception->getCode();

        if ($conn = Transaction::get()) {
            Transaction::rollback();
        }
        return new JsonResponse([
            'status' => 'error',
            'message' => $exception->getMessage(),
        ], $statusCode);
    }
}