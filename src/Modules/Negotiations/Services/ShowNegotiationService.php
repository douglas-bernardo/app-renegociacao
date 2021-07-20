<?php


namespace App\Modules\Negotiations\Services;


use App\Modules\Domain\Infra\Database\Entity\NegotiationsAnalytic;
use App\Modules\Negotiations\Repositories\INegotiationRepository;
use App\Shared\Errors\ApiException;

/**
 * Class ShowNegotiationService
 * @package App\Modules\Negotiations\Services
 */
class ShowNegotiationService
{
    /**
     * @var INegotiationRepository
     */
    private INegotiationRepository $negotiationRepository;

    /**
     * ShowNegotiationService constructor.
     * @param INegotiationRepository $negotiationRepository
     */
    public function __construct(INegotiationRepository $negotiationRepository)
    {
        $this->negotiationRepository = $negotiationRepository;
    }

    /**
     * @param int $id
     * @return NegotiationsAnalytic|null
     * @throws ApiException
     */
    public function execute(int $id): ?NegotiationsAnalytic
    {
        $negotiation = $this->negotiationRepository->findById($id);

        if (!$negotiation) {
            throw new ApiException("Negociação não encontrada");
        }

        return $negotiation;
    }
}