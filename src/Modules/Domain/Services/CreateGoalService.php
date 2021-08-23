<?php

namespace App\Modules\Domain\Services;

use App\Modules\Domain\Infra\Database\Entity\Goal;
use App\Modules\Domain\Repositories\IGoalRepository;
use App\Shared\Errors\ApiException;

class CreateGoalService
{
    private IGoalRepository $goalRepository;

    public function __construct(IGoalRepository $goalRepository)
    {
        $this->goalRepository = $goalRepository;
    }

    /**
     * @throws ApiException
     */
    public function execute(array $data): ?Goal
    {
        $checkGoalExists = $this->goalRepository->findByTypeAndYear(
            $data['goal_type_id'],
            $data['current_year']
        );

        if ($checkGoalExists and $checkGoalExists->active) {
            throw new ApiException('Já existe uma meta ativa cadastrada para esse ano');
        }

        return $this->goalRepository->create($data);
    }
}