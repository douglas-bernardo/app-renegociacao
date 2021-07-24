<?php


namespace App\Modules\Users\Services;


use App\Modules\Users\Infra\Database\Entity\User;
use App\Modules\Users\Repositories\IUserRepository;
use App\Shared\Errors\ApiException;
use Exception;

/**
 * Class UpdateUserService
 * @package App\Modules\Users\Services
 */
class CreateNewPasswordService
{
    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * UpdateUserService constructor.
     * @param IUserRepository $userRepository
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @param int $id
     * @return User|null
     * @throws ApiException
     * @throws Exception
     */
    public function execute(array $data, int $id): ?User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new ApiException("User not found");
        }

        $defaultPassword = password_hash(CONF_PASSWORD_DEFAULT, PASSWORD_DEFAULT);
        $passwordMatched = password_verify($data['password'], $defaultPassword);
        if ($passwordMatched) {
            throw new ApiException("A senha precisa ser diferente da senha padrão", 401);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->fromArray($data);
        $user->reset_password = false;
        $user->store();
        return $user;
    }
}