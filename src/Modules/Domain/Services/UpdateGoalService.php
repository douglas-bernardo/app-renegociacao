<?php


namespace App\Modules\Domain\Services;


use App\Modules\Domain\Infra\Database\Entity\Goal;
use App\Modules\Domain\Infra\Database\Entity\Role;
use App\Modules\Domain\Repositories\IGoalRepository;
use App\Modules\Domain\Repositories\IRoleRepository;
use App\Shared\Errors\ApiException;
use Exception;

class UpdateGoalService
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
    public function execute(array $data, string $id): ?Goal
    {
        $goal = $this->goalRepository->findById($id);

        if (!$goal) {
            throw new ApiException("Goal not found");
        }

        try {
            if (
                isset($data['months']) &&
                is_array($data['months']) &&
                !empty($data['months'])
            ) {
                $goal->delMonths();
            }

            $goal->fromArray($data);
            $goal->store();
            return $goal;
        } catch (Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }
}