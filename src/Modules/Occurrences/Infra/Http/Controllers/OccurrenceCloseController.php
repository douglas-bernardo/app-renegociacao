<?php


namespace App\Modules\Occurrences\Infra\Http\Controllers;


use App\Modules\Occurrences\Services\CloseOccurrenceService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class OccurrenceCloseController
 * @package App\Modules\Occurrences\Infra\Http\Controllers
 */
class OccurrenceCloseController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = $request->attributes->get('user');

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_CONSULTOR'])
            ->can('ocorrenciasFecharSemNegociacao');

        /** @var CloseOccurrenceService $closeOccurrenceService */
        $closeOccurrenceService = $this->containerBuilder->get('closeOccurrence.service');
        $closeOccurrenceService->execute($id, $user);

        Transaction::close();

        return new JsonResponse(['status' => 'success'],202);
    }
}