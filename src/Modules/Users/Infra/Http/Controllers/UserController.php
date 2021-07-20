<?php

namespace App\Modules\Users\Infra\Http\Controllers;

use App\Modules\Users\Services\CreateUserService;
use App\Modules\Users\Services\ListUsersService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserController
 * @package App\Modules\Users\Infra\Http\Controllers
 */
class UserController extends AbstractController
{
    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        Transaction::open($_ENV['APPLICATION']);

        /** @var ListUsersService $listUsersService */
        $listUsersService = $this->containerBuilder->get('listUsers.service');
        $users = $listUsersService->execute();

        Transaction::close();
        return new JsonResponse([
            'status' => 'success',
            'data' => $users
        ],200, ['x-total-count' => count($users)]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        $constraint = new Collection([
            'primeiro_nome' => new NotBlank(),
            'nome' => new NotBlank(),
            'email'=> new Email(),
            'password' => [new NotBlank(), new Length(['min' => 6])]
        ]);
        $constraint->allowExtraFields = true;
        $this->validate($request_data, $constraint);

        Transaction::open($_ENV['APPLICATION']);

        /** @var CreateUserService $createUserService */
        $createUserService = $this->containerBuilder->get('createUser.service');
        $user = $createUserService->execute($request_data)->toArray();

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'user' => $user
        ]);
    }
}
