<?php


namespace App\Modules\Negotiations\Services;


use App\Modules\Negotiations\Infra\Database\Entity\CancelContract;
use App\Modules\Negotiations\Infra\Database\Entity\Negotiation;
use App\Modules\Negotiations\Repositories\INegotiationRepository;
use App\Modules\Occurrences\Repositories\IOccurrenceRepository;
use App\Shared\Errors\ApiException;
use Exception;

/**
 * Class UpdateNegotiationService
 * @package App\Modules\Negotiations\Services
 */
class DeleteNegotiationService
{
    /**
     * @var INegotiationRepository
     */
    private INegotiationRepository $negotiationRepository;

    /**
     * @var IOccurrenceRepository
     */
    private IOccurrenceRepository $occurrenceRepository;

    /**
     * ListNegotiationService constructor.
     * @param INegotiationRepository $negotiationRepository
     * @param IOccurrenceRepository $occurrenceRepository
     */
    public function __construct(
        INegotiationRepository $negotiationRepository,
        IOccurrenceRepository $occurrenceRepository
    )
    {
        $this->negotiationRepository = $negotiationRepository;
        $this->occurrenceRepository = $occurrenceRepository;
    }

    /**
     * @param string $id
     * @throws ApiException
     * @throws Exception
     */
    public function execute(string $id): void
    {
        $negotiation = $this->negotiationRepository->load((int)$id);

        if (!$negotiation) {
            throw new ApiException("Negociação não encontrada");
        }

        if ((int) $negotiation->situacao_id !== 1) {
            throw new ApiException("Negociação já finalizada. Não é permitido a exclusão.");
        }

        $occurrence = $this->occurrenceRepository->findById((int) $negotiation->ocorrencia_id);
        $occurrence->status_ocorrencia_id = 1;
        $occurrence->store();
        $negotiation->delete();
    }
}