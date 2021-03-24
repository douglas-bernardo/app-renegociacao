<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Repository;
use App\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProjetoController
{
    public function index()
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository('App\Model\Projeto');
            $criteria = new Criteria;
            $projetos = $repository->load($criteria);

            if ($projetos) {
                foreach ($projetos as $projeto) {
                    $result[] = $projeto->toArray();
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