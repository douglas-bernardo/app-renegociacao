<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Filter;
use App\Database\Repository;
use App\Database\Transaction;
use App\Log\LoggerTXT;
use App\Model\Projeto;
use App\Model\Situacao;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OcorrenciaController
{
    public function index(Request $request)
    {
        try {

            $user = $request->attributes->get('user');
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            $criteria = new Criteria;
            $offset = isset($query['offset']) ? $query['offset'] : 0;
            $limit = isset($query['limit']) ? $query['limit'] : 10;
            $criteria->setProperty('offset', $offset);
            $criteria->setProperty('limit', $limit);

            $criteria->add(new Filter('idusuario_resp', '=', $user['ts_usuario_id']));
            
            Transaction::open($_ENV['APPLICATION']);
            Transaction::setLogger(new LoggerTXT(__DIR__ . '/../../tmp/ocorrencias.log'));
            
            $repository = new Repository('App\Model\Ocorrencia');          
            $ocorrencias = $repository->load($criteria);
            
            $result = array();
            if ($ocorrencias) {
                foreach ($ocorrencias as $ocorrencia) {
                    $ocorrencia->situacao = (new Situacao($ocorrencia->situacao_id))->toArray();
                    unset($ocorrencia->situacao_id);
                    $result[] = $ocorrencia->toArray();
                }
            }

            $criteria->resetProperties();
            $count = $repository->count($criteria);

            return new JsonResponse([
                'status' => 'success',
                'data' => $result
            ], 200, ['x-total-count' => $count]);

            Transaction::close();
        } catch (\PDOException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($ocorrenciaId)
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $result = array();
            $repository = new Repository('App\Model\Ocorrencia');
            $criteria = new Criteria;
            $criteria->add(new Filter('id', '=', $ocorrenciaId));
            $result = $repository->load($criteria);

            if ($result) {
                $ocorrencia = $result[0];
                $ocorrencia->situacao = (new Situacao($ocorrencia->situacao_id))->toArray();
                $produto = (new Projeto())->loadBy('idprojetots', $ocorrencia->idprojetots);
                $ocorrencia->produto = $produto->nomeprojeto;
                unset($ocorrencia->situacao_id);
                $ocorrencia = $ocorrencia->toArray();
            } else {
                $ocorrencia = null;
            }
            
            return new JsonResponse([
                'status' => 'success',
                'data' => $ocorrencia
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
