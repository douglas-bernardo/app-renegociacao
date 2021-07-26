<?php


namespace App\Modules\Users\Services;


use App\Modules\Users\Repositories\IUserRepository;

/**
 * Class ListUsersService
 * @package App\Modules\Users\Services
 */
class ListUsersService
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
     * @param array $params
     * @return array
     */
    public function execute(array $params = []): array
    {
        return $this->userRepository->findAll($params);
    }
}