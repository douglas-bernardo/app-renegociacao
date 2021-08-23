<?php


namespace App\Modules\Domain\Infra\Database\Repository;


use App\Modules\Domain\Repositories\IDomainRepository;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Repository;
use Exception;

class DomainRepository implements IDomainRepository
{

    /**
     * @throws Exception
     */
    public function loadEntity(string $entity): array
    {
        $result = array();
        $activeRecord = 'App\Modules\Domain\Infra\Database\Entity\\' . $entity;
        $repository = new Repository($activeRecord);
        $criteria = new Criteria();
        $objects = $repository->load($criteria);

        if ($objects) {
            foreach ($objects as $object) {
                $result[] = $object->toArray();
            }
        }
        return $result;
    }
}