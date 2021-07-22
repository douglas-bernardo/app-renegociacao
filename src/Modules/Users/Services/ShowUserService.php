<?php


namespace App\Modules\Users\Services;


use App\Modules\Users\Infra\Database\Entity\User;
use App\Modules\Users\Repositories\IUserRepository;
use App\Shared\Errors\ApiException;

class ShowUserService
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
     * @return User
     * @throws ApiException
     */
    public function execute(int $id): User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new ApiException('Usuário não encontrado');
        }

        return $user;
    }
}