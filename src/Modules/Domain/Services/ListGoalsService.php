<?php

namespace App\Modules\Domain\Services;

use App\Modules\Domain\Repositories\IGoalRepository;

class ListGoalsService
{
    private IGoalRepository $goalRepository;

    public function __construct(IGoalRepository $goalRepository)
    {
        $this->goalRepository = $goalRepository;
    }

    public function execute(): array
    {
        return $this->goalRepository->findAll();
    }
}