<?php


namespace App\Modules\Reports\Infra\Http\Controllers;


use App\Modules\Reports\Infra\Database\Entity\AccumulatedEfficiency;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Repository;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccumulatedEfficiencyController extends AbstractController implements TokenAuthenticatedController
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            Transaction::open($_ENV['APPLICATION']);

            $yearResults = $query['year'] ?? date("Y");
            $repository = new Repository(AccumulatedEfficiency::class, true);
            $repository->addViewParameter('PARAM_ANO_SOLICITACAO', "ano_sol = {$yearResults}");
            $repository->addViewParameter('PARAM_ANO_META', "g.current_year = {$yearResults}");
            $result = $repository->load(new Criteria());

            $dataset = [];
            foreach ($result as $row) $dataset[] = $row->toArray();

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