<?php

namespace App\Modules\Domain\Infra\Database\Repository;

use App\Modules\Domain\Infra\Database\Entity\Goal;
use App\Modules\Domain\Repositories\IGoalRepository;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Filter;
use App\Shared\Infra\Database\Repository;
use Exception;

class GoalRepository implements IGoalRepository
{

    /**
     * @throws Exception
     */
    public function findAll(): array
    {
        $result = [];
        $goals = Goal::all();
        if ($goals) {
            /** @var Goal $goal */
            foreach ($goals as $goal) {
                $result[] = $goal->toArray();
            }
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function create(array $data): ?Goal
    {
        $goal = new Goal();
        $goal->fromArray($data);
        $goal->store();
        return $goal;
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): ?Goal
    {
        $criteria = new Criteria();
        $criteria->add(new Filter('id', '=', $id));
        $repository = new Repository(Goal::class);
        $result = $repository->load($criteria);
        return $result ? $result[0] : null;
    }
}