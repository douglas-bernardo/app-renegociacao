<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Filter;
use App\Database\Repository;
use App\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;

class SituacaoController
{
    public function index()
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository('App\Model\Situacao');
            $criteria = new Criteria;
            $criteria->add(new Filter('id', 'NOT IN', [1, 2, 6, 7]));
            $situacoes = $repository->load($criteria);

            if ($situacoes) {
                foreach ($situacoes as $situacoe) {
                    $result[] = $situacoe->toArray();
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