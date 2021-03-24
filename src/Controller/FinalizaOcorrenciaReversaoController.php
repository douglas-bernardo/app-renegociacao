<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Log\LoggerTXT;
use App\Model\Negociacao;
use App\Model\Ocorrencia;
use App\Model\Reversao;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FinalizaOcorrenciaReversaoController
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
                !isset($request_data['reversao']) ||
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
            $reversao_data = $request_data['reversao'];
            $situacao_data = $request_data['situacao'];

            Transaction::open($_ENV['APPLICATION']);
            // Transaction::setLogger(new LoggerTXT(__DIR__ . '/../../tmp/reversao.log'));

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

            if ((int) $situacao_data['situacao_id'] !== 7) {
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
            $negociacao->reembolso = str_format_currency($negociacao->reembolso);
            $negociacao->taxas_extras = str_format_currency($negociacao->taxas_extras);
            $negociacao->valor_primeira_parcela = str_format_currency($negociacao->valor_primeira_parcela);
            $negociacao->store();

            $reversao = new Reversao();
            $reversao->fromArray($reversao_data);
            $reversao->negociacao_id = $negociacao->id;
            $reversao->valor_venda = str_format_currency($reversao->valor_venda);
            $reversao->store();

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'negociacao' => $negociacao->toArray(),
                    'ocorrencia' => $ocorrencia->toArray(),
                    'reversao' => $reversao->toArray()
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
