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
    public function execute(array $data, string $id): ?Role
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            throw new ApiException("Role not found");
        }

        try {
            if (
                isset($data['permissions']) &&
                is_array($data['permissions']) &&
                !empty($data['permissions'])
            ) {
                $role->delPermissions();
            }

            $role->fromArray($data);
            $role->store();
            return $role;
        } catch (Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }
}