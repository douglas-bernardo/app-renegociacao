<?php


namespace App\Modules\Domain\Services;


use App\Modules\Domain\Infra\Database\Entity\Role;
use App\Modules\Domain\Repositories\IRoleRepository;
use App\Shared\Errors\ApiException;
use Exception;

class ShowRoleService
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
    public function execute(int $id): ?Role
    {
        $role = $this->roleRepository->findById($id);

        if (!$role) {
            throw new ApiException("Cargo/Função não encontrado");
        }

        return $role;
    }
}