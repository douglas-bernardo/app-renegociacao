<?php


namespace App\Modules\Occurrences\Services;


use App\Modules\Negotiations\Infra\Database\Entity\Negotiation;
use App\Modules\Negotiations\Repositories\INegotiationRepository;
use App\Modules\Occurrences\Repositories\IOccurrenceRepository;
use App\Shared\Errors\ApiException;
use Exception;

class RegisterOccurrenceService
{
    private IOccurrenceRepository $occurrenceRepository;
    private INegotiationRepository $negotiationRepository;

    public function __construct(
        IOccurrenceRepository $occurrenceRepository,
        INegotiationRepository $negotiationRepository
    )
    {
        $this->occurrenceRepository = $occurrenceRepository;
        $this->negotiationRepository = $negotiationRepository;
    }

    /**
     * @param int $motivo_id
     * @param int $tipo_solicitacao_id
     * @param int $origem_id
     * @param int $occurrenceId
     * @param array $user
     * @return Negotiation
     * @throws ApiException
     * @throws Exception
     */
    public function execute(
        int $motivo_id,
        int $tipo_solicitacao_id,
        int $origem_id,
        int $occurrenceId,
        array $user
    ): Negotiation
    {
        $occurrence = $this->occurrenceRepository->findById($occurrenceId);
        if ($occurrence->status_ocorrencia_id != 1) {
            throw new ApiException("Ocorrência em negociação ou já finalizada");
        }

        if ($occurrence->idusuario_resp !== $user['ts_usuario_id']) {
            throw new ApiException("Negociações, somente podem ser registradas para ocorrências próprias.");
        }

        $occurrence->status_ocorrencia_id = 2;
        $occurrence->store();

        return $this->negotiationRepository->create(
            $motivo_id,
            $tipo_solicitacao_id,
            $origem_id,
            $user['uid'],
            $occurrenceId
        );
    }
}