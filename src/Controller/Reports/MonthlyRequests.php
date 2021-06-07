<?php


namespace App\Controller\Reports;


use App\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Exception;

class MonthlyRequests
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
                        'Request' => 'Invalid request'
                    ]
                ], 400);
            }

            Transaction::open($_ENV['APPLICATION']);
            $conn = Transaction::get();

            $year = (int) $query['year'];
            $result = $conn->query("SELECT
                                        ciclo_ini as cycle_start,
                                        usuario_resp_negociacao as negotiator_name,
                                        sum(valor_venda) as request_value,
                                        sum(faturamento) as profit,
                                        sum(perda_financeira) as financial_loss_value,
                                        round( (sum(faturamento) / sum(valor_venda)) * 100, 2) AS efficiency,
                                        round( (sum(perda_financeira) / sum(valor_venda)) * 100, 2) AS financial_loss
                                    FROM
                                        vw_analitic
                                    WHERE
                                        ano_sol = {$year}
                                        AND tipo_solicitacao_id IN (2, 4)
                                        AND situacao_id in (1, 2, 6, 7)
                                        AND id_usuario_resp_ts = {$user['ts_usuario_id']}
                                    GROUP BY ciclo_ini, ciclo_ini_num order by ciclo_ini_num");

            $dataset = [];
            foreach ($result as $row) {
                $dataset[] =   [
                    'cycle_start' => $row['cycle_start'],
                    'negotiator_name' => $row['negotiator_name'],
                    'request_value' => $row['request_value'],
                    'profit' => $row['profit'],
                    'financial_loss_value' => $row['financial_loss_value'],
                    'efficiency' => $row['efficiency'],
                    'financial_loss' => $row['financial_loss'],
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