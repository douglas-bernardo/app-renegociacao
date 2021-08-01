<?php


namespace App\Modules\Negotiations\Infra\Http\Controllers;


use App\Modules\Negotiations\Services\DeleteNegotiationService;
use App\Modules\Negotiations\Services\ListNegotiationService;
use App\Modules\Negotiations\Services\ShowNegotiationService;
use App\Modules\Negotiations\Services\UpdateNegotiationService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NegotiationController
 * @package App\Modules\Negotiations\Infra\Http\Controllers
 */
class NegotiationController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @param Request $request
     * @return JsonResponse
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
        $situacao_id = $query['situacao_id'] ?? '0';
        $tipo_solicitacao_id = $query['tipo_solicitacao_id'] ?? '0';
        $userResp = $query['userResp'] ?? 0;

        Transaction::open($_ENV['APPLICATION']);
        $currentUserPermissions = $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR', 'ROLE_CONSULTOR'])
            ->getPermissions();

        /** @var ListNegotiationService $listNegotiationService **/
        $listNegotiationService = $this->containerBuilder->get('listNegotiation.service');
        $result = $listNegotiationService->execute(
            $offset,
            $limit,
            $startDate,
            $endDate,
            $user['uid'],
            $situacao_id,
            $tipo_solicitacao_id,
            $userResp,
            $currentUserPermissions,
        );

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'data' => $result['negotiations']
        ], 200, ['x-total-count' => $result['total']]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function show(string $id): JsonResponse
    {
        Transaction::open($_ENV['APPLICATION']);

        /** @var ShowNegotiationService $showNegotiationService **/
        $showNegotiationService = $this->containerBuilder->get('showNegotiation.service');
        $negotiation = $showNegotiationService->execute($id);
        $result = $negotiation->toArray();

        Transaction::close();

        return new JsonResponse(['status' => 'success', 'data' => $result]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('user');
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);
        $negotiation_data = $request_data['negotiation'];

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR', 'ROLE_CONSULTOR'])
            ->getRoles();

        /** @var UpdateNegotiationService $updateNegotiationService */
        $updateNegotiationService = $this->containerBuilder->get('updateNegotiation.service');
        $negotiation = $updateNegotiationService->execute($negotiation_data, $id, $user);
        $negotiation = $negotiation->toArray();

        Transaction::close();

        return new JsonResponse(['status' => 'success', 'data' => $negotiation]);
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function delete(Request $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('user');
        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR'])
            ->can('negociacaoExcluir');

        /** @var DeleteNegotiationService $deleteNegotiationService */
        $deleteNegotiationService = $this->containerBuilder->get('deleteNegotiation.service');
        $deleteNegotiationService->execute($id);

        Transaction::close();
        return new JsonResponse(['status' => 'success'], 201);
    }
}