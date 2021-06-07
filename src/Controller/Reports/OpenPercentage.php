<?php


namespace App\Controller\Reports;


use App\Database\Repository;
use App\Database\Transaction;
use App\Database\Criteria;
use App\Log\Logger;
use App\Log\LoggerTXT;
use App\Model\AmountOpened;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Exception;

class OpenPercentage
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            Transaction::open($_ENV['APPLICATION']);
            Transaction::setLogger(new LoggerTXT(__DIR__ . '/../../../tmp/open-percentage.log'));

            $yearResults = $query['year'] ?? date("Y");
            $repository = new Repository(AmountOpened::class, true);
            $repository->addViewParameter('PARAM_ANO_SOL', "year(o.dtocorrencia) = {$yearResults}");
            $repository->addViewParameter('PARAM_USER_RESP', "n.usuario_id = {$user['uid']}");

            $result = $repository->load(new Criteria());

            $dataset = [];
            foreach ($result as $row) {
                $dataset[] = $row->toArray();
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