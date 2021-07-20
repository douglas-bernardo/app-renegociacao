<?php


namespace App\Modules\Domain\Repositories;


use App\Modules\Domain\Infra\Database\Entity\Role;

interface IRoleRepository
{
    public function findAll(): array;
    public function create(array $data): ? Role;
    public function findById(int $id): ?Role;
}