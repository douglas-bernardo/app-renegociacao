<?php


namespace App\Modules\Reports\Infra\Http\Controllers;


use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class CashBalanceAdminController extends AbstractController implements TokenAuthenticatedController
{
    public function index(Request $request): JsonResponse
    {
        try {
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
                                                sum(valor_primeira_parcela) as balance
                                            FROM
                                                vw_analitic
                                            WHERE
                                                ano_sol = {$query['year']}
                                                AND tipo_solicitacao_id IN (1, 2, 4)
                                                AND situacao_id in (1, 2, 6, 7)
                                            GROUP BY ciclo_ini");

            $dataset = [];

            foreach ($result as $row) {
                $dataset = [
                    'year' => $query['year'],
                    'cash_balance' => $row['balance']
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