<?php

namespace App\Modules\Domain\Services;

use App\Modules\Domain\Infra\Database\Entity\Goal;
use App\Modules\Domain\Repositories\IGoalRepository;

class CreateGoalService
{
    private IGoalRepository $goalRepository;

    public function __construct(IGoalRepository $goalRepository)
    {
        $this->goalRepository = $goalRepository;
    }

    public function execute(array $data): ?Goal
    {
        return $this->goalRepository->create($data);
    }
}