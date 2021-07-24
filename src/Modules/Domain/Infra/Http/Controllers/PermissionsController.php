<?php


namespace App\Modules\Domain\Infra\Http\Controllers;


use App\Modules\Domain\Services\CreatePermissionService;
use App\Modules\Domain\Services\ListPermissionsService;
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
 * Class PermissionsController
 * @package App\Modules\Domain\Infra\Http\Controllers
 */
class PermissionsController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->attributes->get('user');

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager->getAuthorizations($user['uid'])->is(['ROLE_ADMIN']);

        /** @var ListPermissionsService $listPermissionsService */
        $listPermissionsService = $this->containerBuilder->get('listPermissions.service');
        $permissions = $listPermissionsService->execute();

        Transaction::close();
        return new JsonResponse([
            'status' => 'success',
            'data' => $permissions
        ],200, ['x-total-count' => count($permissions)]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
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
        Transaction::open($_ENV['APPLICATION']);
        $constraint->allowExtraFields = true;
        $this->validate($request_data,  $constraint);

        $this->authorizationManager->getAuthorizations($user['uid'])->is(['ROLE_ADMIN']);

        /** @var CreatePermissionService $createPermissionService */
        $createPermissionService = $this->containerBuilder->get('createPermission.service');
        $permission = $createPermissionService->execute($request_data)->toArray();

        Transaction::close();

        return new JsonResponse(['status' => 'success', 'permission' => $permission]);
    }
}