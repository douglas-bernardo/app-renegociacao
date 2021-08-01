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

class AmountReceivedController extends AbstractController implements TokenAuthenticatedController
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
                                                    situacao_id,
                                                    situacao AS description,
                                                    sum(valor_venda) AS amount_received
                                                FROM 
                                                    vw_analitic
                                                WHERE 
                                                    usuario_id = {$user['uid']}
                                                    AND ano_sol = {$query['year']}
                                                    AND situacao_id IN (1, 2, 6, 7)
                                                GROUP BY 1");

            $dataset = [];
            foreach ($result as $row) {
                $dataset[] =   ['description' => $row['description'], 'total' => $row['amount_received']];
            }

            Transaction::close();
            return new JsonResponse([
                'status' => 'success',
                'data' => $dataset
            ]);

        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}