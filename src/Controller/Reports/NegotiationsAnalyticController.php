<?php


namespace App\Controller\Reports;


use App\Database\Criteria;
use App\Database\Filter;
use App\Database\Repository;
use App\Database\Transaction;
use App\Log\LoggerTXT;
use App\Model\NegotiationsAnalytic;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NegotiationsAnalyticController
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            $criteria = new Criteria;
            $offset = $query['offset'] ?? 0;
            $limit = $query['limit'] ?? 10;

            $criteria->setProperty('offset', $offset);
            $criteria->setProperty('limit', $limit);

            $startDate = $query['startDate'] ?? date('Y-m-d');
            $endDate = $query['endDate'] ?? date('Y-m-d');

            $criteria->add(
                new Filter('id_usuario_resp_ts', '=', $user['ts_usuario_id'])
            );
            $criteria->add(new Filter('data_ocorrencia', '>=', $startDate));
            $criteria->add(new Filter('data_ocorrencia', '<=', $endDate));

            if (isset($query['situacao_id']) && $query['situacao_id'] !== '0') {
                $criteria->add(new Filter('situacao_id', '=', $query['situacao_id']));
            }

            Transaction::open($_ENV['APPLICATION']);
            Transaction::setLogger(new LoggerTXT(__DIR__ . '/../../../tmp/vw_analitic.log'));

            $repository = new Repository(NegotiationsAnalytic::class, true);
            $negotiations = $repository->load($criteria);

            $result = array();
            if ($negotiations) foreach ($negotiations as $negotiation) {
                $result[] = $negotiation->toArray();
            }

            $criteria->resetProperties();
            $count = $repository->count($criteria);

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => $result
            ], 200, ['x-total-count' => $count]);

        } catch (Exception $exception) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}