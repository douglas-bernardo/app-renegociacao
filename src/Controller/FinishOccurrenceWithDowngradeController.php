<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Negociacao;
use App\Model\Ocorrencia;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FinishOccurrenceWithDowngradeController
{
    public function create(Request $request, $ocorrenciaId)
    {
        $user = $request->attributes->get('user');
        $data = $request->toArray();
        $negociacao_data = filter_var_array($data, FILTER_SANITIZE_STRING);

        try {

            Transaction::open($_ENV['APPLICATION']);

            $ocorrencia = new Ocorrencia($ocorrenciaId);

            if ($ocorrencia->open) {
                return new JsonResponse(['error' => 'Occurrence already closed!']);
            }

            if ($ocorrencia->idusuario_resp !== $user['ts_usuario_id']) {
                return new JsonResponse(['error' => 'Only own occurrences can be closed']);
            }

            $negociacao = new Negociacao();
            $negociacao->fromArray($negociacao_data);
            $negociacao->usuario_id = $user['uid'];
            $negociacao->ocorrencia_id = $ocorrencia->id;

            $ocorrencia->situacao_id = $negociacao->situacao_id;
            $ocorrencia->open = 1;
            $ocorrencia->store();

            unset($negociacao->situacao_id);
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
