<?php


namespace App\Modules\Negotiations\Infra\Http\Controllers;


use App\Modules\Negotiations\Services\TransferNegotiationService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class TransferNegotiationController extends AbstractController implements TokenAuthenticatedController
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
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        $constraint_request = new Collection([
            'usuario_novo_id' => new NotBlank(),
            'motivo_transferencia_id' => new NotBlank()
        ]);
        $constraint_request->allowExtraFields = true;
        $this->validate($request_data, $constraint_request);

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN'])
            ->can('negociacaoTransferir');

        /** @var TransferNegotiationService $transferNegotiationService */
        $transferNegotiationService = $this->containerBuilder->get('transferNegotiation.service');
        $negotiation = $transferNegotiationService->execute($id, $request_data, $user['uid'])->toArray();

        Transaction::close();

        return new JsonResponse(['status' => 'success', 'data' => $negotiation], 201);
    }
}