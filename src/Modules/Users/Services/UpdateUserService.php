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
class UpdateUserService
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
    public function execute(array $data, int $id): ? User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new ApiException("User not found");
        }

        if (isset($data['email']) && $data['email'] !== $user->email) {
            $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            $emailExists = $this->userRepository->findByEmail($data['email']);
            if ($emailExists) throw new ApiException("E-mail informado já cadastrado para outro usuário");
        }

        if (isset($data['ts_usuario_id']) && $data['ts_usuario_id'] !== $user->ts_usuario_id) {
            $tsUserIdExists = $this->userRepository->findByTsUserId($data['ts_usuario_id']);
            if ($tsUserIdExists) throw new ApiException("Usuário TS já cadastrado para outro usuário");
        }

        if (isset($data['roles']) && is_array($data['roles']) && !empty($data['roles'])) {
            $user->delRoles();
        }

        if (isset($data['ativo'])) {
            $data['ativo'] = filter_var($data['ativo'], FILTER_VALIDATE_BOOLEAN);
        }

        $user->fromArray($data);
        $user->store();

        return $user;
    }
}