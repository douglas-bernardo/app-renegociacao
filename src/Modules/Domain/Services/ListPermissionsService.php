<?php


namespace App\Modules\Domain\Services;


use App\Modules\Domain\Infra\Database\Entity\Role;
use App\Modules\Domain\Repositories\IPermissionRepository;
use App\Modules\Domain\Repositories\IRoleRepository;

class ListPermissionsService
{
    private IPermissionRepository $permissionRepository;

    public function __construct(IPermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function execute(): array
    {
        return $this->permissionRepository->findAll();
    }
}