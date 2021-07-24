<?php


namespace App\Modules\Users\Services;


use App\Modules\Users\Infra\Database\Entity\User;
use App\Modules\Users\Repositories\IUserRepository;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;

/**
 * Class UpdateUserService
 * @package App\Modules\Users\Services
 */
class ResetPasswordService
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
     * @param int $id
     * @return void
     * @throws ApiException
     * @throws Exception
     */
    public function execute(int $id): void
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new ApiException("User not found");
        }

        $user->password = password_hash(CONF_PASSWORD_DEFAULT, PASSWORD_DEFAULT);
        $user->reset_password = true;

        try {
            $user->store();
        } catch (Exception $e) {
            Transaction::rollback();
            throw new ApiException($e->getMessage());
        }
    }
}