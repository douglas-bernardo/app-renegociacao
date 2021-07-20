<?php


namespace App\Modules\Reports\Infra\Http\Controllers;


use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class MonthlyEfficiencyController
 * @package App\Modules\Reports\Infra\Http\Controllers
 */
class MonthlyEfficiencyController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     */
    public function index(Request $request): JsonResponse
    {
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
                                        ciclo_ini_mes as cycle_start_month,
                                        round( (sum(faturamento) / sum(valor_venda)) * 100, 2) AS efficiency,
                                        round( (sum(perda_financeira) / sum(valor_venda)) * 100, 2) AS financial_loss
                                        FROM
                                            vw_analitic
                                        WHERE
                                            ano_sol = {$query['year']}
                                            AND tipo_solicitacao_id IN (2, 4)
                                            AND situacao_id IN (1, 2, 6, 7)
                                            AND id_usuario_resp_ts = {$user['ts_usuario_id']}
                                        GROUP BY ciclo_ini_num order by ciclo_ini_num");
        $dataset = [];

        foreach ($result as $row) {
            $dataset[] = [
                'cycle' => $row['cycle_start_month'],
                'efficiency' => $row['efficiency'],
                'financial_loss' => $row['financial_loss']
            ];
        }

        Transaction::close();
        return new JsonResponse(['status' => 'success', 'data' => $dataset]);

    }
}