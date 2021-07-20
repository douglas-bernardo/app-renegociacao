<?php


namespace App\Modules\Reports\Infra\Http\Controllers;


use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Infra\Database\Transaction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Exception;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class MonthlyRequestsSevenDaysController extends AbstractController implements TokenAuthenticatedController
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            $constraint = new Collection([
                'year' => new NotBlank()
            ]);
            $constraint->allowExtraFields = true;
            $this->validate($query, $constraint);

            Transaction::open($_ENV['APPLICATION']);
            $conn = Transaction::get();

            $result = $conn->query("SELECT
                                        ciclo_fin as cycle_end,
                                        usuario_resp_negociacao as negotiator_name,
                                        sum(valor_venda) as request_value,
                                        sum(faturamento) as profit,
                                        round( (sum(faturamento) / sum(valor_venda)) * 100, 2) AS efficiency
                                    FROM
                                        vw_analitic
                                    WHERE
                                        ano_sol = {$query['year']}
                                        AND (ano_fin = {$query['year']} or ano_fin is null)
                                        AND tipo_solicitacao_id = 1
                                        AND situacao_id in (1, 2, 6, 7)
                                        AND id_usuario_resp_ts = {$user['ts_usuario_id']}
                                    GROUP BY ciclo_ini_num order by ciclo_ini_num");

            $dataset = [];
            foreach ($result as $row) {
                $dataset[] =   [
                    'cycle_end' => $row['cycle_end'],
                    'negotiator_name' => $row['negotiator_name'],
                    'request_value' => $row['request_value'],
                    'profit' => $row['profit'],
                    'efficiency' => $row['efficiency']
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