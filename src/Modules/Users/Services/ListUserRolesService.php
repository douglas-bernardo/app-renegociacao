<?php


namespace App\Modules\Users\Services;


use App\Modules\Users\Repositories\IUserRepository;
use App\Shared\Errors\ApiException;
use Exception;

/**
 * Class ListUsersService
 * @package App\Modules\Users\Services
 */
class ListUserRolesService
{
    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * ListUsersService constructor.
     * @param IUserRepository $userRepository
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $id
     * @return array
     * @throws ApiException
     * @throws Exception
     */
    public function execute(int $id): array
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new ApiException("Current user not found", 401);
        }

        $roles = [];
        foreach ($user->getRoles() as $role) $roles[] = $role->toArray();

        return $roles;
    }
}