<?php


namespace App\Modules\Domain\Repositories;


use App\Modules\Domain\Infra\Database\Entity\Permission;

interface IPermissionRepository
{
    public function findAll(): array;
    public function create(string $name, string $description): ? Permission;
}