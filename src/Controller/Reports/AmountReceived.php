<?php


namespace App\Controller\Reports;


use App\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Exception;

class AmountReceived
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            Transaction::open($_ENV['APPLICATION']);
            $conn = Transaction::get();

            $result = $conn->query("SELECT 
                                        vw.situacao_id,
                                        vw.situacao AS description,
                                        sum(vw.valor_venda) AS amount_received
                                    FROM 
                                        vw_analitic vw
                                    WHERE 
                                        vw.id_usuario_resp_ts = {$user['ts_usuario_id']}
                                        AND vw.ano_sol = {$query['year']}
                                        AND vw.situacao_id IN (1, 2, 6, 7)
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