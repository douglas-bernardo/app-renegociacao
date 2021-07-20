<?php


namespace App\Modules\Reports\Infra\Http\Controllers;


use App\Modules\Reports\Infra\Database\Entity\AmountOpened;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Facades\Log\Log;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Repository;
use App\Shared\Infra\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Exception;

class OpenPercentageController extends AbstractController implements TokenAuthenticatedController
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            Transaction::open($_ENV['APPLICATION']);
            Transaction::setLogger(Log::getInstance());

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