<?php


namespace App\Modules\Domain\Infra\Http\Controllers;


use App\Modules\Domain\Services\CreateRoleService;
use App\Modules\Domain\Services\ListRolesService;
use App\Modules\Domain\Services\UpdateRoleService;
use App\Modules\Users\Services\ShowUserService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RolesController
 * @package App\Modules\Domain\Infra\Http\Controllers
 */
class RolesController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @throws ApiException
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->attributes->get('user');

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager->getAuthorizations($user['uid'])->is(['ROLE_ADMIN']);

        /** @var ListRolesService $listRolesService */
        $listRolesService = $this->containerBuilder->get('listRoles.service');
        $roles = $listRolesService->execute();

        Transaction::close();
        return new JsonResponse([
            'status' => 'success',
            'data' => $roles
        ],200, ['x-total-count' => count($roles)]);
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        $user = $request->attributes->get('user');
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        $constraint = new Collection([
            'name' => new NotBlank()
        ]);
        $constraint->allowExtraFields = true;
        $this->validate($request_data, $constraint);

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager->getAuthorizations($user['uid'])->is(['ROLE_ADMIN']);

        /** @var CreateRoleService $createRoleService */
        $createRoleService = $this->containerBuilder->get('createRole.service');
        $role = $createRoleService->execute($request_data)->toArray();

        Transaction::close();
        return new JsonResponse(['status' => 'success', 'role' => $role]);
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        Transaction::open($_ENV['APPLICATION']);
        /** @var UpdateRoleService $updateRoleService */
        $updateRoleService = $this->containerBuilder->get('updateRole.service');
        $role = $updateRoleService->execute($request_data, $id)->toArray();

        Transaction::close();

        return new JsonResponse(['status' => 'success', 'role' => $role]);
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('user');

        Transaction::open($_ENV['APPLICATION']);

        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR'])
            ->can('configuracoesFuncoesVer');

        /** @var ShowUserService $showRoleService */
        $showRoleService = $this->containerBuilder->get('showRole.service');
        $role = $showRoleService->execute((int)$id)->toArray();

        Transaction::close();

        return new JsonResponse(['status' => 'success', 'data' => $role]);
    }
}