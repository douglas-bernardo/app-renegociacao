<?php


namespace App\Modules\Domain\Infra\Database\Repository;


use App\Modules\Domain\Infra\Database\Entity\Role;
use App\Modules\Domain\Repositories\IRoleRepository;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Filter;
use App\Shared\Infra\Database\Repository;
use Exception;

class RoleRepository implements IRoleRepository
{
    /**
     * @throws Exception
     */
    public function findAll(): array
    {
        $result = [];
        $roles = Role::all();
        if ($roles) {
            /** @var Role $role */
            foreach ($roles as $role) {
                $result[] = $role->toArray();
            }
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function create(array $data): ?Role
    {
        $role = new Role();
        $role->fromArray($data);
        $role->alias = 'ROLE_' . strtoupper(str_slug_underscore($role->name));
        $role->store();
        return $role;
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): ?Role
    {
        $criteria = new Criteria();
        $criteria->add(new Filter('id', '=', $id));
        $repository = new Repository(Role::class);
        $result = $repository->load($criteria);
        return $result ? $result[0] : null;
    }

    /**
     * @throws Exception
     */
    public function findByAlias(string $aliasName): ?Role
    {
        $criteria = new Criteria();
        $criteria->add(new Filter('alias', '=', $aliasName));
        $repository = new Repository(Role::class);
        $result = $repository->load($criteria);
        return $result ? $result[0] : null;
    }
}