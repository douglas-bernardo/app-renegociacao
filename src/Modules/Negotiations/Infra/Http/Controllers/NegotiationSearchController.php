<?php


namespace App\Modules\Negotiations\Infra\Http\Controllers;


use App\Modules\Negotiations\Services\SearchNegotiationService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NegotiationSearchController
 * @package App\Modules\Negotiations\Infra\Http\Controllers
 */
class NegotiationSearchController extends AbstractController implements TokenAuthenticatedController
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->attributes->get('user');
        $ts_usuario_id = (int)$user['ts_usuario_id'];

        $query = $request->query->all();
        $query = filter_var_array($query, FILTER_SANITIZE_STRING);

        $search_param = trim($query['param'] ?? '');

        Transaction::open($_ENV['APPLICATION']);
        $currentUserRoles = $this->authorizationManager->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_CONSULTOR'])
            ->getRoles();

        /** @var SearchNegotiationService $searchNegotiationService */
        $searchNegotiationService = $this->containerBuilder->get('searchNegotiation.service');
        $result = $searchNegotiationService->execute($ts_usuario_id, $search_param, $currentUserRoles);

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'data' => $result['negotiations']
        ], 200, ['x-total-count' => $result['total']]);
    }
}