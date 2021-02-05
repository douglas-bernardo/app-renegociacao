<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Repository;
use App\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;

class NegociacaoController
{
    public function index()
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository('App\Model\Negociacao');
            $criteria = new Criteria;
            $negociacoes = $repository->load($criteria);

            if ($negociacoes) {
                foreach ($negociacoes as $negociacao) {
                    $result[] = $negociacao->toArray();
                }
            }

            return new JsonResponse([
                'status' => 'success',
                'total' => count($result),
                'data' => $result
            ]);

            Transaction::close();
        } catch (\PDOException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
