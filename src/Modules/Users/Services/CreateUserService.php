<?php


namespace App\Modules\Users\Services;


use App\Modules\Users\Infra\Database\Entity\User;
use App\Modules\Users\Repositories\IUserRepository;
use App\Shared\Errors\ApiException;

class CreateUserService
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
     * @param array $data
     * @return User
     * @throws ApiException
     */
    public function execute(array $data): User
    {
        $userExists = $this->userRepository->findByEmail($data['email']);

        if ($userExists) {
            throw new ApiException("Email address already exists!");
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['user_timesharing']);
        return $this->userRepository->create($data);
    }
}