<?php


namespace App\Modules\Domain\Services;


use App\Modules\Domain\Repositories\IDomainRepository;

class DomainServices
{
    private IDomainRepository $domainRepository;

    public function __construct(IDomainRepository $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    public function execute(string $entity): array
    {
        return $this->domainRepository->loadEntity($entity);
    }
}