<?php


namespace App\Controller\Reports;


use App\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccumulatedProfit
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            if (!isset($query['year'])) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Request' => 'Invalid request. Parameter year is required'
                    ]
                ], 400);
            }

            Transaction::open($_ENV['APPLICATION']);
            $conn = Transaction::get();

            $result = $conn->query("SELECT
                                        ciclo_fin as cycle_end,
                                        usuario_resp_negociacao as negotiator_name,
                                        sum(valor_venda) as request_amount,
                                        sum(valor_retido) as kept_amount,
                                        sum(valor_venda_novo) as new_contract_value,
                                        sum(perda_financeira) as financial_loss_amount,
                                        sum(taxas_extras) as extra_rate,
                                        sum(multa) as fine,
                                        sum(reembolso) as refund
                                    FROM
                                        vw_analitic
                                    WHERE
                                        ano_sol = {$query['year']}
                                        AND (ano_fin = {$query['year']} or ano_fin is null)
                                        AND tipo_solicitacao_id IN (2, 4)
                                        AND situacao_id in (1, 2, 6, 7)
                                        AND id_usuario_resp_ts = {$user['ts_usuario_id']}
                                    GROUP BY ciclo_fin, ciclo_ini_num order by ciclo_ini_num");

            $dataset = [];
            foreach ($result as $row) {
                $dataset[] = [
                    'cycle_end' => $row['cycle_end'],
                    'negotiator_name' => $row['negotiator_name'],
                    'request_amount' => $row['request_amount'],
                    'kept_amount' => $row['kept_amount'],
                    'new_contract_value' => $row['new_contract_value'],
                    'financial_loss_amount' => $row['financial_loss_amount'],
                    'extra_rate' => $row['extra_rate'],
                    'fine' => $row['fine'],
                    'refund' => $row['refund'],
                ];
            }

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => $dataset
            ]);

        } catch (Exception $exception) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}