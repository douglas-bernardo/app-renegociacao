<?php


namespace App\Modules\Negotiations\Infra\Http\Controllers;


use App\Modules\Negotiations\Services\RestoreNegotiationService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestoreNegotiationController extends AbstractController implements TokenAuthenticatedController
{
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

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN'])
            ->can('negociacaoRestaurar');

        /** @var RestoreNegotiationService $restoreNegotiationService */
        $restoreNegotiationService = $this->containerBuilder->get('restoreNegotiation.service');
        $negotiation = $restoreNegotiationService->execute($id)->toArray();

        Transaction::close();

        return new JsonResponse(['status' => 'success', 'data' => $negotiation]);
    }
}