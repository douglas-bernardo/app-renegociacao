<?php


namespace App\Modules\Negotiations\Infra\Http\Controllers;


use App\Modules\Negotiations\Services\DefaultNegotiationCloseService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Exception;

class DefaultNegotiationCloseController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @throws ApiException
     * @throws Exception
     */
    public function create(Request $request, string  $id): JsonResponse
    {
        $user = $request->attributes->get('user');
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        $this->validate($request_data, new Collection(['negotiation' => new NotBlank()]));

        $constraint =  new Collection([
            'situacao_id' => new NotBlank(),
            'tipo_contato_id' => new NotBlank()
        ]);
        $constraint->allowExtraFields = true;
        $this->validate($request_data['negotiation'], $constraint);

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_CONSULTOR'])
            ->getRoles();

        /** @var DefaultNegotiationCloseService $defaultNegotiationCloseService */
        $defaultNegotiationCloseService = $this->containerBuilder->get('defaultNegotiationClose.service');
        $defaultNegotiationCloseService->execute($request_data['negotiation'], $id, $user);

        Transaction::close();

        return new JsonResponse(['status' => 'success'], 202);
    }
}
