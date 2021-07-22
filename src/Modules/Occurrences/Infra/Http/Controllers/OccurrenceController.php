<?php

namespace App\Modules\Occurrences\Infra\Http\Controllers;

use App\Modules\Occurrences\Services\ListOccurrenceService;
use App\Modules\Occurrences\Services\ShowOccurrenceService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class OccurrenceController
 * @package App\Modules\Occurrences\Infra\Http\Controllers
 */
class OccurrenceController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->attributes->get('user');
        $query = $request->query->all();
        $query = filter_var_array($query, FILTER_SANITIZE_STRING);

        $offset = $query['offset'] ?? 0;
        $limit = $query['limit'] ?? 10;
        $startDate = $query['startDate'] ?? date('Y-m-d');
        $endDate = $query['endDate'] ?? date('Y-m-d');
        $status = $query['status'] ?? '0';
        $userResp = $query['userResp'] ?? 0;

        Transaction::open($_ENV['APPLICATION']);

        $currentUserPermissions = $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR', 'ROLE_CONSULTOR'])
            ->can('ocorrenciasVer')
            ->getPermissions();

        /** @var ListOccurrenceService $listOccurrenceService */
        $listOccurrenceService = $this->containerBuilder->get('listOccurrence.service');
        $result = $listOccurrenceService->execute(
            $offset,
            $limit,
            $startDate,
            $endDate,
            $status,
            $userResp,
            $user['ts_usuario_id'] ?? null,
            $currentUserPermissions
        );

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'data' => $result['occurrences']
        ], 200, ['x-total-count' => $result['total']]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->attributes->get('user');

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR', 'ROLE_CONSULTOR'])
            ->can('ocorrenciasVer')
            ->getRoles();

        /** @var ShowOccurrenceService $showOccurrenceService */
        $showOccurrenceService = $this->containerBuilder->get('showOccurrence.service');
        $occurrence = $showOccurrenceService->execute($id)->toArray();

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'data' => $occurrence
        ]);
    }
}
