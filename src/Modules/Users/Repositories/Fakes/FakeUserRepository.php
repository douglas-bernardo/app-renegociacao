<?php

namespace App\Modules\Users\Repositories\Fakes;

use App\Modules\Users\Infra\Database\Entity\User;

class FakeUserRepository implements \App\Modules\Users\Repositories\IUserRepository
{
    private array $users = [];
    /**
     * @inheritDoc
     */
    public function findAll(array $params = []): array
    {
        return $this->users;
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): ?User
    {
        foreach ($this->users as $user) if ($user->email === $email) {
            return $user;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function findByTsUserId(int $TsUserId): ?User
    {
        foreach ($this->users as $user) if ($user->ts_usuario_id === $TsUserId) {
            return $user;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function findById(int $id): ?User
    {
        foreach ($this->users as $user) if ($user->id === $id) {
            return $user;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): ?User
    {
        $user = new User();
        $user->fromArray($data);
        $this->users[] = $user;
        return $user;
    }
}