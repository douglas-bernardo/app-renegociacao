<?php


namespace App\Modules\Users\Infra\Http\Controllers;


use App\Modules\Users\Services\CreateNewPasswordService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateNewPasswordController extends AbstractController implements TokenAuthenticatedController
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
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        $constraint = new Collection([
            'password' => [new NotBlank(), new Length(['min' => 6])]
        ]);
        $constraint->allowExtraFields = true;
        $this->validate($request_data, $constraint);

        Transaction::open($_ENV['APPLICATION']);

        /** @var CreateNewPasswordService $createNewPasswordService */
        $createNewPasswordService = $this->containerBuilder->get('createNewPassword.service');
        $createNewPasswordService->execute($request_data, (int) $id);

        Transaction::close();
        return new JsonResponse(['status' => 'success'], 201);
    }
}