<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Negociacao;
use App\Model\Ocorrencia;
use App\Model\Retencao;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FinalizaOcorrenciaRetencaoController
{
    public function create(Request $request, $ocorrenciaId)
    {
        try {

            $user = $request->attributes->get('user');

            $request_data = $request->toArray();
            $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

            if (
                !isset($request_data['negociacao']) ||
                !isset($request_data['retencao']) ||
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
            $retencao_data = $request_data['retencao'];
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

            if ((int) $situacao_data['situacao_id'] !== 6) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Finalização' => 'Finalização inválida!'
                    ]
                ]);
            }

            $ocorrencia->situacao_id = $situacao_data['situacao_id'];
            $ocorrencia->finalizada = true;
            $ocorrencia->store();

            $negociacao = new Negociacao();
            $negociacao->fromArray($negociacao_data);
            $negociacao->usuario_id = $user['uid'];
            $negociacao->ocorrencia_id = $ocorrencia->id;
            $negociacao->data_finalizacao = date("Y-m-d H:i:s");
            $negociacao->valor_primeira_parcela = ($negociacao->valor_primeira_parcela) ? str_format_currency($negociacao->valor_primeira_parcela) : '0.00';
            $negociacao->store();

            $retencao = new Retencao();
            $retencao->fromArray($retencao_data);
            $retencao->valor_financiado = ($retencao->valor_financiado) ? str_format_currency($retencao->valor_financiado) : '0.00';
            $retencao->negociacao_id = $negociacao->id;
            $retencao->store();

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'nogociacao' => $negociacao->toArray(),
                    'ocorrencia' => $ocorrencia->toArray(),
                    'retencao' => $retencao->toArray(),
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
