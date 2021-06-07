<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Filter;
use App\Database\Repository;
use App\Database\Transaction;
use App\Model\StatusOccurrence;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatusOccurrenceController
{
    public function index(): JsonResponse
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository(StatusOccurrence::class);
            $criteria = new Criteria;
            $status_types = $repository->load($criteria);

            if ($status_types) {
                foreach ($status_types as $type) {
                    $result[] = $type->toArray();
                }
            }

            return new JsonResponse([
                'status' => 'success',
                'data' => $result
            ], 200, ['x-total-count' => count($result)]);

            Transaction::close();
        } catch (\PDOException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}