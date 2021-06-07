<?php


namespace App\Controller\Reports;


use App\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MonthlyRequestsSummary
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

            $result = $conn->query("SELECT
                                        ciclo_ini as cycle_start,
                                        sum(valor_venda) as request_amount,
                                        sum(faturamento) as profit,
                                        sum(perda_financeira) as financial_loss_amount,
                                        sum(valor_primeira_parcela) as balance
                                    FROM
                                        vw_analitic
                                    WHERE
                                        ano_sol = {$query['year']}
                                        AND tipo_solicitacao_id IN (2, 4)
                                        AND situacao_id in (1, 2, 6, 7)
                                        AND id_usuario_resp_ts = {$user['ts_usuario_id']}
                                    GROUP BY 1");

            $dataset = [];
            foreach ($result as $row) {
                $dataset[] =   [
                    'cycle_start' => $row['cycle_start'],
                    'request_amount' => $row['request_amount'],
                    'profit' => $row['profit'],
                    'financial_loss_amount' => $row['financial_loss_amount'],
                    'balance' => $row['balance']
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