<?php


namespace App\Modules\Domain\Infra\Database\Entity;


use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Filter;
use App\Shared\Infra\Database\Record;
use App\Shared\Infra\Database\Repository;
use Exception;
use InvalidArgumentException;

/**
 * Class Role
 * @package App\Modules\Domain\Infra\Database\Entity
 */
class Role extends Record
{
    /**
     *
     */
    const TABLENAME = 'role';

    /**
     * @return void
     * @throws Exception
     */
    public function store()
    {
        parent::store();

        if (isset($this->permissions) && !is_array($this->permissions)) {
            throw new InvalidArgumentException('Permissions parameter must be array');
        }

        if (isset($this->permissions) && !empty($this->permissions)) {
            foreach ($this->permissions as $permission) {
                $rolePermission = new RolePermission();
                $rolePermission->role_id = $this->id;
                $rolePermission->permission_id = $permission;
                $rolePermission->store();
            }
        }
    }

    /**
     * @throws Exception
     */
    public function getRolePermissions(): array
    {
        $repository = new Repository(RolePermission::class);
        $criteria = new Criteria();
        $criteria->add(new Filter('role_id', '=', $this->id));
        return $repository->load($criteria);
    }

    /**
     * @throws Exception
     */
    public function getPermissions(): array
    {
        $rolePermissions = $this->getRolePermissions();
        $permissions = [];
        if ($rolePermissions) {
            foreach ($rolePermissions as $rolePermission) $permissions[] = new Permission($rolePermission->permission_id);
        }
        return $permissions;
    }


    /**
     * @throws Exception
     */
    public function delPermissions(): void
    {
        $criteria = new Criteria();
        $criteria->add(new Filter('role_id', '=', $this->id));
        $repository = new Repository(RolePermission::class);
        $repository->delete($criteria);
    }

    /**
     * @throws Exception
     */
    public function toArray(): array
    {
        $rolePermissions = $this->getRolePermissions();
        $permissions = [];
        if ($rolePermissions) {
            /** @var RolePermission $rp */
            foreach ($rolePermissions as $rp) {
                $permissions[] = (new Permission($rp->permission_id))->toArray();
            }
        }
        $this->permissions = $permissions;
        return parent::toArray();
    }
}