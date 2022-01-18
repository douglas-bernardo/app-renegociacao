<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Repositories\Fakes\FakeUserRepository;
use PHPUnit\Framework\TestCase;

class AuthenticateUserServiceTest extends TestCase
{
    public function testItShouldBeAbleToAuthenticate()
    {
        $fakeUserRepository = new FakeUserRepository();
        $auth = new AuthenticateUserService($fakeUserRepository);

        $userData = [
            "primeiro_nome" => "JOHN DOE",
            "nome" => "JOHN DOE",
            "email" => "johndoe@beachpark.com.br",
            "password" => password_hash('123456', PASSWORD_DEFAULT),
            "ts_usuario_id" => "99999",
            "usuariots" => "johndoe",
        ];

        $user = $fakeUserRepository->create($userData);

        $response = $auth->execute('johndoe@beachpark.com.br', '123456');

        $this->assertArrayHasKey('token', $response);
        $this->assertEquals('JOHN DOE', $response['user']->nome);
    }
}
