<?php


namespace App\Modules\Negotiations\Services;


use App\Modules\Negotiations\Infra\Database\Entity\Negotiation;
use App\Modules\Negotiations\Infra\Database\Entity\TransferNegotiation;
use App\Modules\Negotiations\Repositories\INegotiationRepository;
use App\Modules\Users\Infra\Database\Entity\User;
use App\Shared\Errors\ApiException;
use Exception;

class RestoreNegotiationService
{
    /**
     * @var INegotiationRepository
     */
    private INegotiationRepository $negotiationRepository;

    /**
     * ListNegotiationService constructor.
     * @param INegotiationRepository $negotiationRepository
     */
    public function __construct(INegotiationRepository $negotiationRepository)
    {
        $this->negotiationRepository = $negotiationRepository;
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function execute(int $id): Negotiation
    {
        $negotiation = $this->negotiationRepository->load($id);

        if (!$negotiation) {
            throw new ApiException("Negociação não encontrada");
        }

        if ((int) $negotiation->situacao_id === 1) {
            throw new ApiException("Negociação já está aguardando retorno");
        }

        $negotiation->restore();
        $negotiation->store();

        return $negotiation;
    }
}