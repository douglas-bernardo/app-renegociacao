<?php


namespace App\Modules\Domain\Infra\Database\Repository;


use App\Modules\Domain\Infra\Database\Entity\Permission;
use App\Modules\Domain\Repositories\IPermissionRepository;
use Exception;

/**
 * Class PermissionRepository
 * @package App\Modules\Domain\Infra\Database\Repository
 */
class PermissionRepository implements IPermissionRepository
{
    /**
     * @return array
     * @throws Exception
     */
    public function findAll(): array
    {
        $result = [];
        $permissions = Permission::all();
        if ($permissions) {
            /** @var Permission $permission */
            foreach ($permissions as $permission) {
                $result[] = $permission->toArray();
            }
        }
        return $result;
    }

    /**
     * @param string $name
     * @param string $description
     * @return Permission|null
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function create(string $name, string $description = ''): ?Permission
    {
        $permission = new Permission();
        $permission->name        = $name;
        $permission->description = $description;
        $permission->key_word    = str_camel_case($name);
        $permission->store();
        return $permission;
    }
}