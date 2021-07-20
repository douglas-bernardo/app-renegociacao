<?php


namespace App\Modules\Domain\Services;


use App\Modules\Domain\Infra\Database\Entity\Role;
use App\Modules\Domain\Repositories\IRoleRepository;
use App\Shared\Errors\ApiException;
use Exception;

class UpdateRoleService
{
    private IRoleRepository $roleRepository;

    public function __construct(IRoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function execute(array $data, string $id): ? Role
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            throw new ApiException("Role not found");
        }

        $currentPermissions = $role->getPermissions();
        $currentPermissionsIds = [];
        foreach ($currentPermissions as $permission) $currentPermissionsIds[] = $permission->id;
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $uniques = array_diff($data['permissions'], $currentPermissionsIds);
            $data['permissions'] = $uniques;
        }

        $role->fromArray($data);
        $role->store();
        return $role;
    }
}