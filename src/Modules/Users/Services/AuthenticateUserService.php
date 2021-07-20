<?php


namespace App\Modules\Users\Services;


use App\Modules\Users\Repositories\IUserRepository;
use App\Shared\Errors\ApiException;
use DateTimeImmutable;
use Exception;

/**
 * Class AuthenticateUserService
 * @package App\Modules\Users\Services
 */
class AuthenticateUserService
{
    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * AuthenticateUserService constructor.
     * @param IUserRepository $userRepository
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws ApiException
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function execute(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new ApiException("E-mail ou senha incorretos", 401);
        }

        $passwordMatched = password_verify($password, $user->password);

        if (!$passwordMatched) {
            throw new ApiException("E-mail ou senha incorretos", 401);
        }

        $now = new DateTimeImmutable();
        $tokenIdentity = md5(uniqid(rand(), true));
        $config = getConfigJWT();

        $token = $config->builder()
            ->issuedBy('http://app.renegociacao')
            ->identifiedBy($tokenIdentity)
            ->expiresAt($now->modify('+1 day'))
            ->withClaim('ts_usuario_id', $user->ts_usuario_id)
            ->withClaim('uid', $user->id)
            ->withHeader('tokenIdentity', $tokenIdentity)
            ->getToken($config->signer(), $config->signingKey());

        return ['user' => $user, 'token' => $token->toString()];
    }
}