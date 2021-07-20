<?php


namespace App\Modules\Negotiations\Services;


use App\Modules\Negotiations\Infra\Database\Entity\DowngradeContract;
use App\Modules\Negotiations\Repositories\INegotiationRepository;
use App\Shared\Errors\ApiException;
use Exception;

class DowngradeContractService
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
    public function execute(array $data, string $id, array $user): void
    {
        $negotiation = $this->negotiationRepository->load((int) $id);

        if (!$negotiation) {
            throw new ApiException("Negociação não encontrada");
        }

        if ($negotiation->usuario_id !== $user['uid']) {
            throw new ApiException("Somente negociações próprias podem ser finalizadas");
        }

        if ((int) $negotiation->situacao_id !== 1) {
            throw new ApiException("Negociação já finalizada");
        }

        if ((int) $data['negotiation']['situacao_id'] !== 7) {
            throw new ApiException("Situação escolhida inválida");
        }

        $negotiation->fromArray($data['negotiation']);
        $negotiation->data_finalizacao = date("Y-m-d");
        $negotiation->store();

        $downgradeContract = new DowngradeContract();
        $downgradeContract->fromArray($data['downgrade']);
        $downgradeContract->negociacao_id = $negotiation->id;
        $downgradeContract->store();
    }
}