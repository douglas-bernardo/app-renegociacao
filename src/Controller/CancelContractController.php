<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\CancelContract;
use App\Model\Negotiation;
use App\Model\Occurrence;
use PDOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CancelContractController
{
    public function create(Request $request, $negotiationId): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $request_data = $request->toArray();
            $request_data = filter_var_array(
                $request_data,
                FILTER_SANITIZE_STRING
            );

            if (
                !isset($request_data['negotiation']) ||
                !isset($request_data['cancel'])
            ) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Request' => 'Invalid request'
                    ]
                ], 400);
            }

            $negotiation_data = $request_data['negotiation'];
            $cancel_data = $request_data['cancel'];

            if ((int) $negotiation_data['situacao_id'] !== 2) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'situation' => 'situation type invalid'
                    ]
                ],400);
            }

            Transaction::open($_ENV['APPLICATION']);
            $negotiation = new Negotiation($negotiationId);
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
            $negotiation->data_finalizacao = date("Y-m-d H:i:s", CONF_DATE_FIN);
            $negotiation->reembolso = str_format_currency($negotiation_data['reembolso']);
            $negotiation->numero_pc = $negotiation_data['numero_pc'];
            $negotiation->taxas_extras = str_format_currency($negotiation_data['taxas_extras']);
            $negotiation->observacao = $negotiation_data['observacao'];
            $negotiation->store();

            $cancel = new CancelContract();
            $cancel->negociacao_id = $negotiation->id;
            $cancel->multa = str_format_currency($cancel_data['multa']);
            $cancel->store();

//            $occurrence = new Occurrence($negotiation->ocorrencia_id);
//            $occurrence->status_ocorrencia_id = 3;
//            $occurrence->store();

            Transaction::close();

            return new JsonResponse(['status' => 'success'],202);
        } catch (PDOException $e) {
            Transaction::rollback();
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
