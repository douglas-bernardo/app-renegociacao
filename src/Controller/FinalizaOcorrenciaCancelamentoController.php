<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Cancelamento;
use App\Model\Negociacao;
use App\Model\Ocorrencia;
use App\Model\Reversao;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FinalizaOcorrenciaCancelamentoController
{
    public function create(Request $request, $ocorrenciaId)
    {
        try {

            $user = $request->attributes->get('user');
            $request_data = $request->toArray();
            $request_data = filter_var_array(
                $request_data,
                FILTER_SANITIZE_STRING
            );

            if (
                !isset($request_data['negociacao']) ||
                !isset($request_data['cancelamento']) ||
                !isset($request_data['situacao'])
            ) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Requisição' => 'Requisição inválida!'
                    ]
                ]);
            }

            $negociacao_data = $request_data['negociacao'];
            $cancelamento_data = $request_data['cancelamento'];
            $situacao_data = $request_data['situacao'];

            Transaction::open($_ENV['APPLICATION']);

            $ocorrencia = new Ocorrencia($ocorrenciaId);
            if ($ocorrencia->finished) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Ocorrência' => 'Ocorrência Já finalizada!'
                    ]
                ]);
            }

            if ($ocorrencia->idusuario_resp !== $user['ts_usuario_id']) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Ocorrência' => 'Somente ocorrências próprias podem ser finalizadas!'
                    ]
                ]);
            }

            if ((int) $situacao_data['situacao_id'] !== 2) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Finalização' => 'Finalização inválida!'
                    ]
                ]);
            }

            $ocorrencia->situacao_id = $situacao_data['situacao_id'];
            $ocorrencia->finished = 1;
            $ocorrencia->store();

            $negociation = new Negociacao();
            $negociation->fromArray($negociacao_data);
            $negociation->usuario_id = $user['uid'];
            $negociation->ocorrencia_id = $ocorrencia->id;
            $negociation->store();

            $cancelamento = new Cancelamento();
            $cancelamento->fromArray($cancelamento_data);
            $cancelamento->negociacao_id = $negociation->id;
            $cancelamento->store();

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'negociacao' => $negociation->toArray(),
                    'ocorrencia' => $ocorrencia->toArray(),
                    'cancelamento' => $cancelamento->toArray()
                ]
            ]);
        } catch (\PDOException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
