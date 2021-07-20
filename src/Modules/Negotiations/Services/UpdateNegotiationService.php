<?php


namespace App\Modules\Negotiations\Services;


use App\Modules\Negotiations\Infra\Database\Entity\CancelContract;
use App\Modules\Negotiations\Infra\Database\Entity\Negotiation;
use App\Modules\Negotiations\Repositories\INegotiationRepository;
use App\Shared\Errors\ApiException;
use Exception;

/**
 * Class UpdateNegotiationService
 * @package App\Modules\Negotiations\Services
 */
class UpdateNegotiationService
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
     * @param array $data
     * @param string $id
     * @param array $user
     * @return Negotiation
     * @throws ApiException
     * @throws Exception
     */
    public function execute(array $data, string $id, array $user): Negotiation
    {
        $negotiation = $this->negotiationRepository->load((int)$id);

        if (!$negotiation) {
            throw new ApiException("Negociação não encontrada");
        }

        if ($negotiation->usuario_id !== $user['uid']) {
            throw new ApiException("Somente negociações próprias podem ser alteradas");
        }

        $negotiation->fromArray($data);

        if ((int)$negotiation->situacao_id === 2) {
            $cancelContract = (new CancelContract())->loadBy('negociacao_id', $negotiation->id);
            if ($cancelContract) {
                $cancelContract->multa = $data['multa'] ?? null;
                $cancelContract->store();
            }
            unset($negotiation->multa);
        }
        $negotiation->store();
        return $negotiation;
    }
}