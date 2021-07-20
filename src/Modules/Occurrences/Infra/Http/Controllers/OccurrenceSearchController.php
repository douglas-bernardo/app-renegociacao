<?php

namespace App\Modules\Occurrences\Infra\Http\Controllers;

use App\Modules\Occurrences\Services\SearchOccurrenceService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class OccurrenceSearchController
 * @package App\Modules\Occurrences\Infra\Http\Controllers
 */
class OccurrenceSearchController extends AbstractController implements TokenAuthenticatedController
{
    /**
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
        $currentUserRoles = $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_CONSULTOR'])
            ->getRoles();

        /** @var SearchOccurrenceService $searchOccurrenceService */
        $searchOccurrenceService = $this->containerBuilder->get('searchOccurrence.service');
        $result = $searchOccurrenceService->execute($ts_usuario_id, $search_param, $currentUserRoles);

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'data' => $result['occurrences']
        ], 200, ['x-total-count' => $result['total']]);
    }
}
