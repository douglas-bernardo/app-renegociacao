<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Repository;
use App\Database\Transaction;
use App\Model\RequestSource;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class RequestSourceController
{
    public function index(): JsonResponse
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository(RequestSource::class);
            $criteria = new Criteria;
            $requestSources = $repository->load($criteria);

            if ($requestSources) {
                foreach ($requestSources as $requestSource) {
                    $result[] = $requestSource->toArray();
                }
            }

            return new JsonResponse([
                'status' => 'success',
                'data' => $result
            ], 200, ['x-total-count' => count($result)]);

            Transaction::close();
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}