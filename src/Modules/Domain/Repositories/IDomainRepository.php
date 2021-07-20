<?php


namespace App\Modules\Domain\Repositories;


interface IDomainRepository
{
    public function loadEntity(string $entity): array;
}