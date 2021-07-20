<?php


namespace App\Modules\Occurrences\Infra\Http\Controllers;


use App\Modules\Occurrences\Services\RegisterOccurrenceService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class OccurrenceRegisterController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @throws ApiException
     * @throws Exception
     */
    public function create(Request $request, $occurrenceId): JsonResponse
    {
        $user = $request->attributes->get('user');
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        $this->validate($request_data, new Collection([
            'motivo_id' => new NotBlank(),
            'tipo_solicitacao_id' => new NotBlank(),
            'origem_id' => new NotBlank()
        ]));

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_CONSULTOR'])
            ->can('ocorrenciasRegistrarNegociacao');

        /** @var RegisterOccurrenceService $registerOccurrenceService **/
        $registerOccurrenceService = $this->containerBuilder->get('registerOccurrenceService.service');
        $negotiation = $registerOccurrenceService->execute(
            $request_data['motivo_id'],
            $request_data['tipo_solicitacao_id'],
            $request_data['origem_id'],
            $occurrenceId,
            $user
        )->toArray();

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'user' => $negotiation
        ]);
    }
}