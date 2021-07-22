<?php


namespace App\Modules\Users\Repositories;


use App\Modules\Users\Infra\Database\Entity\User;

/**
 * Interface IUserRepository
 * @package App\Modules\Users\Repositories
 */
interface IUserRepository
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * @param int $TsUserId
     * @return User|null
     */
    public function findByTsUserId(int $TsUserId): ?User;

    /**
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     * @param array $data
     * @return User|null
     */
    public function create(array $data): ?User;
}