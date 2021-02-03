<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Negociacao;
use App\Model\Ocorrencia;
use App\Model\Retencao;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FinishOccurrenceWithMaintainedContractController
{
    public function create(Request $request, $ocorrenciaId)
    {
        try {
            $user = $request->attributes->get('user');

            $request_data = $request->toArray();
            $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

            $negociation_data = $request_data['negociation'];
            $retention_data = $request_data['retention'];
            $situation_data = $request_data['situation'];

            Transaction::open($_ENV['APPLICATION']);

            $ocorrencia = new Ocorrencia($ocorrenciaId);

            if ($ocorrencia->finished) {
                return new JsonResponse(['error' => 'Occurrence already closed!']);
            }

            if ($ocorrencia->idusuario_resp !== $user['ts_usuario_id']) {
                return new JsonResponse(['error' => 'Only own occurrences can be closed']);
            }

            if ((int) $situation_data['situacao_id'] !== 6) {
                return new JsonResponse(['error' => 'Invalid situation id']);
            }

            $ocorrencia->situacao_id = $situation_data['situacao_id'];
            $ocorrencia->finished = 1;
            $ocorrencia->store();

            $negociation = new Negociacao();
            $negociation->fromArray($negociation_data);
            $negociation->usuario_id = $user['uid'];
            $negociation->ocorrencia_id = $ocorrencia->id;
            $negociation->store();     

            $retention = new Retencao();
            $retention->fromArray($retention_data);
            $retention->negociacao_id = $negociation->id;
            $retention->store();

            Transaction::close();

            return new JsonResponse([
                'uid' => $user['uid'],
                'occurrence' => $ocorrencia->toArray(),
                'negotiation' => $negociation->toArray(),
                'retention' => $retention->toArray(),
            ]);
        } catch (\PDOException $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }
    }
}
