<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Negotiation;
use App\Model\Occurrence;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RegisterNegotiationController
{
    public function create(Request $request, $occurrenceId): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $request_data = $request->toArray();
            $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

            if (
                !isset($request_data['motivo_id']) ||
                !isset($request_data['tipo_solicitacao_id']) ||
                !isset($request_data['origem_id'])
            ) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Requisição' => 'Invalid request!'
                    ]
                ], 400);
            }

            Transaction::open($_ENV['APPLICATION']);

            $occurrence = new Occurrence($occurrenceId);
            if ($occurrence->status_ocorrencia_id != 1) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Occurrence' => 'Occurrence on negotiation or already finalized'
                    ]
                ], 400);
            }

            if ($occurrence->idusuario_resp !== $user['ts_usuario_id']) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Occurrence' => 'Only own occurrences can be finalized'
                    ]
                ], 403);
            }

            $occurrence->status_ocorrencia_id = 2;

            $negotiation = new Negotiation();
            $negotiation->fromArray($request_data);
            $negotiation->usuario_id = $user['uid'];
            $negotiation->ocorrencia_id = $occurrence->id;
            $negotiation->situacao_id = 1;

            $occurrence->store();
            $negotiation->store();

            Transaction::close();

            return new JsonResponse(['status' => 'success'],202);
        } catch (\PDOException $e) {
            Transaction::rollback();
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
