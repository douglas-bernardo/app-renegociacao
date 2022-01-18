<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Repositories\Fakes\FakeUserRepository;
use PHPUnit\Framework\TestCase;

class ListUsersServiceTest extends TestCase
{
    public function testItShouldBeAbleToListUsers()
    {
        $fakeUserRepository = new FakeUserRepository();
        $createUserService = new CreateUserService($fakeUserRepository);

        $listUsersService = new ListUsersService($fakeUserRepository);

        $user1 = $fakeUserRepository->create([
            "primeiro_nome" => "JOHN DOE",
            "nome" => "JOHN DOE",
            "email" => "johndoe@beachpark.com.br",
            "password" => password_hash('123456', PASSWORD_DEFAULT),
            "ts_usuario_id" => "99999",
            "usuariots" => "johndoe",
        ]);

        $user2 = $fakeUserRepository->create([
            "primeiro_nome" => "JOHN DOE",
            "nome" => "JOHN DOE",
            "email" => "johndoe@beachpark.com.br",
            "password" => password_hash('123456', PASSWORD_DEFAULT),
            "ts_usuario_id" => "99999",
            "usuariots" => "johndoe",
        ]);

        $listUsers = $listUsersService->execute();

        $this->assertIsBool(in_array([$user1, $user2], $listUsers));
    }
}
