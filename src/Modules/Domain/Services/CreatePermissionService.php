<?php


namespace App\Modules\Domain\Services;


use App\Modules\Domain\Infra\Database\Entity\Permission;
use App\Modules\Domain\Repositories\IPermissionRepository;

class CreatePermissionService
{
    private IPermissionRepository $permissionRepository;

    public function __construct(IPermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function execute(array $data): ? Permission
    {
        $name = $data['name'];
        $description = $data['description'] ?? '';
        return $this->permissionRepository->create($name, $description);
    }
}