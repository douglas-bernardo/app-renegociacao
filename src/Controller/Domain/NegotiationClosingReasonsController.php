<?php

namespace App\Controller\Domain;

use App\Database\Criteria;
use App\Database\Repository;
use App\Database\Transaction;
use App\Model\Reasons;
use PDOException;
use Symfony\Component\HttpFoundation\JsonResponse;

class NegotiationClosingReasonsController
{
    public function index(): JsonResponse
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository(Reasons::class);
            $criteria = new Criteria;
            $reasons = $repository->load($criteria);

            if ($reasons) {
                foreach ($reasons as $reason) {
                    $result[] = $reason->toArray();
                }
            }

            return new JsonResponse([
                'status' => 'success',
                'data' => $result
            ], 200, ['x-total-count' => count($result)]);

            Transaction::close();
        } catch (PDOException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}