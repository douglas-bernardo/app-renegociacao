<?php


namespace App\Modules\Users\Services;


use App\Modules\Domain\Repositories\IPermissionRepository;
use App\Modules\Domain\Repositories\IRoleRepository;
use App\Modules\Users\Infra\Database\Entity\User;
use App\Modules\Users\Repositories\IUserRepository;
use App\Shared\Errors\ApiException;

class BootService
{
    private IRoleRepository $roleRepository;
    private IPermissionRepository $permissionRepository;
    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;


    /**
     * ListUsersService constructor.
     * @param IRoleRepository $roleRepository
     * @param IPermissionRepository $permissionRepository
     * @param IUserRepository $userRepository
     */
    public function __construct(
        IRoleRepository $roleRepository,
        IPermissionRepository $permissionRepository,
        IUserRepository $userRepository
    )
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $adminRoleData
     * @param array $adminUserData
     * @return User
     * @throws ApiException
     */
    public function execute(array $adminRoleData, array $adminUserData): User
    {
        $permissions = $this->permissionRepository->findAll();
        if (empty($permissions)) {
            throw new ApiException("Permissions was not registered!");
        }

        $userExists = $this->userRepository->findByEmail($adminUserData['email']);
        if ($userExists) {
            throw new ApiException("Boot system already executed!");
        }

        $permissionsIds = [];
        foreach ($permissions as $permission) $permissionsIds[] = $permission['id'];
        $adminRoleData['permissions'] = $permissionsIds;

        $role = $this->roleRepository->create($adminRoleData);
        if (!$role) {
            throw new ApiException("Role creation failed!");
        }

        $adminUserData['roles'] = [$role->id];
        $adminUserData['password'] = password_hash($adminUserData['password'], PASSWORD_DEFAULT);
        return $this->userRepository->create($adminUserData);
    }
}