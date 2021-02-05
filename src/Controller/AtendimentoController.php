<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Repository;
use App\Database\Transaction;
use App\Model\Atendimento;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AtendimentoController
{

    public function index()
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository('App\Model\Atendimento');
            $criteria = new Criteria;
            $atendimentos = $repository->load($criteria);

            if ($atendimentos) {
                foreach ($atendimentos as $atendimento) {
                    $result[] = $atendimento->toArray();
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

    public function create(Request $request)
    {
        try {

            $data = $request->toArray();
            $data = filter_var_array($data, FILTER_SANITIZE_STRING);

            if (
                !isset($data['ocorrencia_id'])
            ) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Requisição' => 'Requisição inválida!'
                    ]
                ]);
            }

            Transaction::open($_ENV['APPLICATION']);

            $atendimento = (new Atendimento())->fromArray($data);
            $atendimento->store();

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'atendimento' => $atendimento->toArray()
            ]);
        } catch (Exception $e) {
            Transaction::rollback();
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
