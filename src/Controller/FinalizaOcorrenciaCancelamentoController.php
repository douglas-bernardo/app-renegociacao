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
                    ], 400);
            }

            $negociacao_data = $request_data['negociacao'];
            $cancelamento_data = $request_data['cancelamento'];
            $situacao_data = $request_data['situacao'];

            Transaction::open($_ENV['APPLICATION']);

            $ocorrencia = new Ocorrencia($ocorrenciaId);
            if ($ocorrencia->finalizada) {
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
                    ], 400);
            }

            $ocorrencia->situacao_id = $situacao_data['situacao_id'];
            $ocorrencia->finalizada = true;
            $ocorrencia->store();

            $negociacao = new Negociacao();
            $negociacao->fromArray($negociacao_data);
            $negociacao->usuario_id = $user['uid'];
            $negociacao->ocorrencia_id = $ocorrencia->id;
            $negociacao->data_finalizacao = date("Y-m-d H:i:s");
            $negociacao->reembolso = str_format_currency($negociacao->reembolso);
            $negociacao->taxas_extras = str_format_currency($negociacao->taxas_extras);
            $negociacao->store();

            $cancelamento = new Cancelamento();
            $cancelamento->fromArray($cancelamento_data);
            $cancelamento->negociacao_id = $negociacao->id;
            $cancelamento->multa = str_format_currency($cancelamento->multa);
            $cancelamento->store();

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'negociacao' => $negociacao->toArray(),
                    'ocorrencia' => $ocorrencia->toArray(),
                    'cancelamento' => $cancelamento->toArray()
                ]
            ]);
        } catch (\PDOException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
