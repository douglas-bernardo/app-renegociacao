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
     * @param array $rolesData
     * @param array $adminUserData
     * @return User
     * @throws ApiException
     */
    public function execute(array $rolesData, array $adminUserData): User
    {
        $userExists = $this->userRepository->findByEmail($adminUserData['email']);
        if ($userExists) {
            throw new ApiException("Boot system already executed!");
        }

        if (empty($rolesData)) {
            throw new ApiException("Roles data can not be empty");
        }

        if (empty($adminUserData)) {
            throw new ApiException("Admin data can not be empty");
        }

        $permissions = $this->permissionRepository->findAll();
        if (empty($permissions)) {
            throw new ApiException("Permissions was not created");
        }

        try {
            foreach ($rolesData as $role) {
                $this->roleRepository->create($role);
            }

            $admin = $this->roleRepository->findByAlias('ROLE_ADMIN');
            if (!$admin) {
                throw new ApiException("Admin role creation failed!");
            }

            $permissionsIds = [];
            foreach ($permissions as $permission) $permissionsIds[] = $permission['id'];
            $admin->permissions = $permissionsIds;
            $admin->store();

            $adminUserData['roles'] = [$admin->id];
            $adminUserData['password'] = password_hash($adminUserData['password'], PASSWORD_DEFAULT);
            return $this->userRepository->create($adminUserData);

        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }
}