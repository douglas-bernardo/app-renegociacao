<?php


namespace App\Shared\Bundle\Security;


use App\Modules\Domain\Infra\Database\Entity\Permission;
use App\Modules\Domain\Infra\Database\Entity\Role;
use App\Modules\Users\Infra\Database\Entity\User;
use App\Shared\Errors\ApiException;
use Exception;
use InvalidArgumentException;

class AuthorizationManager
{
    private static AuthorizationManager $instance;
    private array $roles = [];

    private function __construct()
    {
    }

    /**
     * @return AuthorizationManager
     */
    public static function getInstance(): AuthorizationManager
    {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function getAuthorizations(int $userId): AuthorizationManager
    {
        $user = new User($userId);
        if (!$user) {
            throw new ApiException('Unauthorized. User not found.');
        }
        $this->roles = $user->getRoles();
        return self::$instance;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = [];
        foreach ($this->roles as $role) $roles[] = $role->alias;
        return $roles;
    }

    /**
     * @throws ApiException
     */
    public function is(array $roles): AuthorizationManager
    {
        if (empty($roles)) {
            throw new InvalidArgumentException('Array roles can not be empty');
        }

        $currentRoles = [];
        foreach ($this->roles as $role) $currentRoles[] = $role->alias;

        $hasRoles = array_intersect($roles, $currentRoles);
        if (empty($hasRoles)) {
            throw new ApiException('Not Authorized');
        }
        return self::$instance;
    }

    /**
     * @throws Exception
     */
    public function can(string $permissionName): AuthorizationManager
    {
        $permissions = [];
        /** @var Role $role */
        foreach ($this->roles as $role) {
            foreach ($role->getPermissions() as $permission) {
                $permissions[] = $permission;
            }
        };
        $currentPermissions = [];
        /** @var Permission $permission */
        foreach ($permissions as $permission) $currentPermissions[] = $permission->key_word;

        $hasPermission = in_array($permissionName, array_unique($currentPermissions));

        if (!$hasPermission) {
            throw new ApiException('Not Authorized');
        }
        return self::$instance;
    }
}