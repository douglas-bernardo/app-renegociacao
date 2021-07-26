<?php

namespace App\Modules\Users\Infra\Http\Controllers;

use App\Modules\Users\Services\CreateUserService;
use App\Modules\Users\Services\ListUserRolesService;
use App\Modules\Users\Services\ListUsersService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Facades\ContainerDependenceInjection\ContainerDependenceInjection;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
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
class UserRoleController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function index(Request $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('user');

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN'])->can('configuracoesUsuariosEditar');

        /** @var ListUserRolesService $listUserRolesService */
        $listUserRolesService = $this->containerBuilder->get('listUserRoles.service');
        $user_roles = $listUserRolesService->execute((int) $id);

        Transaction::close();
        return new JsonResponse(['status' => 'success', 'data' => $user_roles]);
    }
}
