<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Negotiation;
use App\Model\DowngradeContract;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DowngradeContractController
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
                !isset($request_data['downgrade'])
            ) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'request' => 'invalid request'
                    ]
                    ], 400);
            }

            $negotiation_data = $request_data['negotiation'];
            $downgrade_data = $request_data['downgrade'];

            if ((int) $negotiation_data['situacao_id'] !== 7) {
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
            $negotiation->valor_primeira_parcela = str_format_currency(
                $negotiation_data['valor_primeira_parcela']
            );
            $negotiation->observacao = $negotiation_data['observacao'];
            $negotiation->data_finalizacao = date("Y-m-d H:i:s", CONF_DATE_FIN);
            $negotiation->reembolso = str_format_currency($negotiation_data['reembolso']);
            $negotiation->numero_pc = $negotiation_data['numero_pc'];
            $negotiation->taxas_extras = str_format_currency($negotiation_data['taxas_extras']);
            $negotiation->store();

            $downgradeContract = new DowngradeContract();
            $downgradeContract->fromArray($downgrade_data);
            $downgradeContract->negociacao_id = $negotiation->id;
            $downgradeContract->valor_venda = str_format_currency($downgrade_data['valor_venda']);
            $downgradeContract->store();

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
