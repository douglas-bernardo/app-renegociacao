<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Repository;
use App\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;

class MotivoController
{
    public function index()
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository('App\Model\Motivo');
            $criteria = new Criteria;
            $motivos = $repository->load($criteria);

            if ($motivos) {
                foreach ($motivos as $motivo) {
                    $result[] = $motivo->toArray();
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