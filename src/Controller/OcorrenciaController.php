<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Filter;
use App\Database\Repository;
use App\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OcorrenciaController
{
    public function index(Request $request)
    {
        $user = $request->attributes->get('user');

        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository('App\Model\Ocorrencia');
            $criteria = new Criteria;
            $criteria->add(new Filter('idusuario_resp', '=', $user['ts_usuario_id']));
            $criteria->add(new Filter('finished', '=', false));
            $ocorrencias = $repository->load($criteria);

            if ($ocorrencias) {
                foreach ($ocorrencias as $ocorrencia) {
                    $result[] = $ocorrencia->toArray();
                }
            }

            return new JsonResponse([
                'total' => count($result),
                'data' => $result
            ]);

            Transaction::close();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function show($ocorrenciaId)
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $repository = new Repository('App\Model\Ocorrencia');
            $criteria = new Criteria;
            $criteria->add(new Filter('id', '=', $ocorrenciaId));
            $result = $repository->load($criteria);

            $ocorrencia = $result ? $result[0]->toArray() : null;

            return new JsonResponse([
                'total' => count($result),
                'data' => $ocorrencia
            ]);

            Transaction::close();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }
}
