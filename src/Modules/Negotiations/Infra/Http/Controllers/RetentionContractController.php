<?php

namespace App\Modules\Negotiations\Infra\Http\Controllers;


use App\Modules\Negotiations\Services\RetentionContractService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class RetentionContractController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @throws ApiException
     * @throws Exception
     */
    public function create(Request $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('user');
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        $constraint_request =  new Collection([
            'negotiation' => new NotBlank(),
            'retention' => new NotBlank()
        ]);
        $this->validate($request_data, $constraint_request);

        $constraint_negotiation = new Collection([
            'situacao_id' => new NotBlank(),
            'tipo_contato_id' => new NotBlank()
        ]);
        $constraint_negotiation->allowExtraFields = true;
        $this->validate($request_data['negotiation'], $constraint_negotiation);

        $constraint_retention = new Collection([
            'valor_financiado' => new NotBlank(),
        ]);
        $constraint_retention->allowExtraFields = true;
        $this->validate($request_data['retention'], $constraint_retention);

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_CONSULTOR'])
            ->getRoles();

        /** @var RetentionContractService $retentionContractService */
        $retentionContractService = $this->containerBuilder->get('retentionContract.service');
        $retentionContractService->execute($request_data, $id, $user);

        Transaction::close();

        return new JsonResponse(['status' => 'success'], 202);
    }
}
