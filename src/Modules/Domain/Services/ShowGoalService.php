<?php


namespace App\Modules\Domain\Services;


use App\Modules\Domain\Infra\Database\Entity\Goal;
use App\Modules\Domain\Repositories\IGoalRepository;
use App\Shared\Errors\ApiException;
use Exception;

class ShowGoalService
{
    private IGoalRepository $goalRepository;

    public function __construct(IGoalRepository $goalRepository)
    {
        $this->goalRepository = $goalRepository;
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function execute(int $id): ?Goal
    {
        $goal = $this->goalRepository->findById($id);

        if (!$goal) throw new ApiException("Goal not found");

        return $goal;
    }
}