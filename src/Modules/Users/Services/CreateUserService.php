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
        if (isset($data['ts_usuario_id']) && !empty($data['ts_usuario_id'])) {
            $tsUserExists = $this->userRepository->findByTsUserId($data['ts_usuario_id']);
            if ($tsUserExists) throw new ApiException("Usuário TS já Cadastrado");
        }

        $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $userExists = $this->userRepository->findByEmail($data['email']);
        if ($userExists) {
            throw new ApiException("E-mail informado já cadastrado para outro usuário");
        }

        $data['password'] = password_hash(CONF_PASSWORD_DEFAULT, PASSWORD_DEFAULT);
        return $this->userRepository->create($data);
    }
}