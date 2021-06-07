<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Negotiation;
use App\Model\Occurrence;
use App\Model\RetentionContract;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RetentionContractController
{
    public function create(Request $request, $negotiationId): JsonResponse
    {
        try {
            $request_data = $request->toArray();
            $request_data = filter_var_array(
                $request_data, FILTER_SANITIZE_STRING
            );

            if (
                !isset($request_data['negotiation']) ||
                !isset($request_data['retention'])
            ) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'request' => 'invalid request'
                    ]
                ], 400);
            }

            $negotiation_data = $request_data['negotiation'];
            if ((int) $negotiation_data['situacao_id'] !== 6) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'situation' => 'situation type invalid'
                    ]
                ], 400);
            }

            Transaction::open($_ENV['APPLICATION']);
            $negotiation = new Negotiation($negotiationId);

            $user = $request->attributes->get('user');
            if ($negotiation->usuario_id !== $user['uid']) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'negotiation' => 'only own negotiations can be finalized'
                    ]
                ], 403);
            }

            if ((int) $negotiation->situacao_id !== 1) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'negotiation' => 'negotiation already finalized'
                    ]
                ], 403);
            }

            $negotiation->situacao_id = $negotiation_data['situacao_id'];
            $negotiation->tipo_contato_id = $negotiation_data['tipo_contato_id'];
            $negotiation->valor_primeira_parcela = str_format_currency(
                $negotiation_data['valor_primeira_parcela']
            );
            $negotiation->observacao = $negotiation_data['observacao'];
            $negotiation->data_finalizacao = date("Y-m-d H:i:s", CONF_DATE_FIN);
            $negotiation->store();

            $retention_data = $request_data['retention'];
            $retention = new RetentionContract();
            $retention->valor_financiado = str_format_currency($retention_data['valor_financiado']);
            $retention->negociacao_id = $negotiation->id;
            $retention->store();

//            $occurrence = new Occurrence($negotiation->ocorrencia_id);
//            $occurrence->status_ocorrencia_id = 3;
//            $occurrence->store();

            Transaction::close();

            return new JsonResponse(['status' => 'success'],202);
        } catch (Exception $e) {
            Transaction::rollback();
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
