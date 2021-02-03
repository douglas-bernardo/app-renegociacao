<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Negociacao;
use App\Model\Ocorrencia;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FinishOccurrenceDefaultController
{
    public function create(Request $request, $ocorrenciaId)
    {
        try {
            $user = $request->attributes->get('user');
            $request_data = $request->toArray();

            $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

            $negociation_data = $request_data['negociation'];
            $situation_data = $request_data['situation'];

            Transaction::open($_ENV['APPLICATION']);

            $ocorrencia = new Ocorrencia($ocorrenciaId);

            if ($ocorrencia->finished) {
                return new JsonResponse(['error' => 'Occurrence already closed!']);
            }

            if ($ocorrencia->idusuario_resp !== $user['ts_usuario_id']) {
                return new JsonResponse(['error' => 'Only own occurrences can be closed']);
            }
            
            $invalid_situation_ids = [1, 6 , 7];
            $situation_id = (int) $situation_data['situacao_id'];
            if (in_array($situation_id, $invalid_situation_ids)) {
                return new JsonResponse(['error' => 'Invalid situation id']);
            }

            $ocorrencia->situacao_id = $situation_data['situacao_id'];
            $ocorrencia->finished = 1;
            $ocorrencia->store();

            $negociacao = new Negociacao();
            $negociacao->fromArray($negociation_data);
            $negociacao->usuario_id = $user['uid'];
            $negociacao->ocorrencia_id = $ocorrencia->id;
            $negociacao->store();

            Transaction::close();

            return new JsonResponse([
                'uid' => $user['uid'],
                'occurrence' => $ocorrencia->toArray(),
                'negotiation' => $negociacao->toArray()
            ]);
        } catch (\PDOException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }
    }
}
