<?php

namespace App\Modules\Users\Infra\Http\Controllers;

use App\Modules\Users\Services\CreateUserService;
use App\Modules\Users\Services\ListUsersService;
use App\Modules\Users\Services\ShowUserService;
use App\Modules\Users\Services\UpdateUserService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
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
class UserController extends AbstractController implements TokenAuthenticatedController
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
        Transaction::open($_ENV['APPLICATION']);

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


        /** @var CreateUserService $createUserService */
        $createUserService = $this->containerBuilder->get('createUser.service');
        $user = $createUserService->execute($request_data)->toArray();

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('user');

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN'])
            ->getRoles();

        /** @var ShowUserService $showUserService */
        $showUserService = $this->containerBuilder->get('showUser.service');
        $user = $showUserService->execute($id)->toArray();

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'data' => $user
        ]);
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
        $request_data['ativo'] = filter_var($request_data['ativo'], FILTER_VALIDATE_BOOLEAN);

        Transaction::open($_ENV['APPLICATION']);

        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN'])->can('configuracoesUsuariosEditar');

        /** @var UpdateUserService $updateUserService */
        $updateUserService = $this->containerBuilder->get('updateUser.service');
        $user = $updateUserService->execute($request_data, (int) $id)->toArray();

        Transaction::close();
        return new JsonResponse(['status' => 'success', 'data' => $user]);
    }
}
