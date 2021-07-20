<?php

namespace App\Modules\Negotiations\Infra\Http\Controllers;


use App\Modules\Negotiations\Services\CancelContractService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class CancelContractController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @throws ApiException
     * @throws Exception
     */
    public function create(Request $request, $id): JsonResponse
    {
        $user = $request->attributes->get('user');
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        $constraint_request = new Collection(['negotiation' => new NotBlank()]);
        $constraint_request->allowExtraFields = true;
        $this->validate($request_data, $constraint_request);

        $constraint = new Collection([
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

        /** @var CancelContractService $cancelContractService */
        $cancelContractService = $this->containerBuilder->get('cancelContract.service');
        $cancelContractService->execute($request_data, $id, $user);

        Transaction::close();
        return new JsonResponse(['status' => 'success'], 202);
    }
}
