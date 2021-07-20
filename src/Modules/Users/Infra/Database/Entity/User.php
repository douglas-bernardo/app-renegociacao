<?php

namespace App\Modules\Users\Infra\Database\Entity;


use App\Modules\Domain\Infra\Database\Entity\Role;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Filter;
use App\Shared\Infra\Database\Record;
use App\Shared\Infra\Database\Repository;
use Exception;

/**
 * Class User
 * @package App\Modules\Users\Infra\Database\Entity
 */
class User extends Record
{
    /**
     *
     */
    const TABLENAME = 'usuario';

    /**
     * @return void
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function store()
    {
        parent::store();
        if (isset($this->roles) && !empty($this->roles)) {
            foreach ($this->roles as $role) {
                $userRole = new UserRole();
                $userRole->user_id = $this->id;
                $userRole->role_id = $role;
                $userRole->store();
            }
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getRoles(): array
    {
        $userRoles = $this->getUserRoles();
        $roles = [];
        if ($userRoles) {
            foreach ($userRoles as $userRole) $roles[] = new Role($userRole->role_id);
        }
        return $roles;
    }

    /**
     * @return array
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function getUserRoles(): array
    {
        $repository = new Repository(UserRole::class);
        $criteria = new Criteria();
        $criteria->add(new Filter('user_id', '=', $this->id));
        return $repository->load($criteria);
    }

    /**
     * @return array
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function toArray(): array
    {
        $userRoles = $this->getUserRoles();
        $roles = [];
        if ($userRoles) {
            foreach ($userRoles as $userRole) {
                $roles[] = (new Role($userRole->role_id))->alias;
            }
        }
        $this->roles = $roles;
        unset($this->password);
        return parent::toArray();
    }
}
