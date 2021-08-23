<?php

namespace App\Modules\Domain\Infra\Database\Entity;

use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Filter;
use App\Shared\Infra\Database\Record;
use App\Shared\Infra\Database\Repository;
use Exception;
use InvalidArgumentException;

class Goal extends Record
{
    const TABLENAME = 'goal';

    /**
     * @throws Exception
     */
    public function getGoalMonths(): array
    {
        $repository = new Repository(GoalMonth::class);
        $criteria = new Criteria();
        $criteria->add(new Filter('goal_id', '=', $this->id));
        return $repository->load($criteria);
    }

    public function store()
    {
        parent::store();

        if (isset($this->months) && !is_array($this->months)) {
            throw new InvalidArgumentException('Months parameter must be array');
        }

        if (isset($this->months) && !empty($this->months)) {
            foreach ($this->months as $month) {
                $goalMonth = new GoalMonth();
                $goalMonth->goal_id = $this->id;
                $goalMonth->month_number = $month['month_number'];
                $goalMonth->target = $month['target'];
                $goalMonth->store();
            }
        }
    }

    /**
     * @throws Exception
     */
    public function delMonths(): void
    {
        $criteria = new Criteria();
        $criteria->add(new Filter('goal_id', '=', $this->id));
        $repository = new Repository(GoalMonth::class);
        $repository->delete($criteria);
    }

    /**
     * @throws Exception
     */
    public function toArray(): array
    {
        $goalMonths = $this->getGoalMonths();
        $months = [];
        /** @var GoalMonth $goalMonth */
        if ($goalMonths) {
            foreach ($goalMonths as $goalMonth) {
                $months[] = $goalMonth->toArray();
            }
        }
        $this->goal_type = (new GoalType($this->goal_type_id))->toArray();
        $this->months = $months;
        return parent::toArray();
    }
}