<?php


namespace App\Modules\Domain\Services;


use App\Modules\Domain\Infra\Database\Entity\Role;
use App\Modules\Domain\Repositories\IRoleRepository;

class ListRolesService
{
    private IRoleRepository $roleRepository;

    public function __construct(IRoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function execute(): array
    {
        return $this->roleRepository->findAll();
    }
}