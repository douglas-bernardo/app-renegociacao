<?php

namespace App\Modules\Domain\Repositories;

use App\Modules\Domain\Infra\Database\Entity\Goal;

interface IGoalRepository
{
    public function findAll(): array;
    public function create(array $data): ?Goal;
    public function findById(int $id): ?Goal;
    public function findByTypeAndYear(int $goal_type_id, int $current_year): ?Goal;
}