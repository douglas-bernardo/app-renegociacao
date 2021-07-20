<?php


namespace App\Modules\Reports\Infra\Http\Controllers;



use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Infra\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Exception;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class MonthlyRequestsController extends AbstractController implements TokenAuthenticatedController
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