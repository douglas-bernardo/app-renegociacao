<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Negotiation;
use App\Model\Occurrence;
use PDOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultNegotiationCloseController
{
    public function create(Request $request, $negotiationId): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $request_data = $request->toArray();
            $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

            if (!isset($request_data['negotiation'])) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'request' => 'invalid request'
                    ]
                ],400);
            }

            $negotiation_data = $request_data['negotiation'];

            $invalid_situation_ids = [1, 2, 6, 7];
            $situation_id = (int) $negotiation_data['situacao_id'];
            if (in_array($situation_id, $invalid_situation_ids)) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'situation' => 'situation type invalid'
                    ]
                ]);
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
            $negotiation->observacao = $negotiation_data['observacao'];
            $negotiation->store();

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
            ]);
        }
    }
}
