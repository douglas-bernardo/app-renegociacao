<?php


namespace App\Modules\Negotiations\Services;


use App\Modules\Negotiations\Repositories\INegotiationRepository;

/**
 * Class ListNegotiationService
 * @package App\Modules\Negotiations\Services
 */
class ListNegotiationService
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
     * @param int $offset
     * @param int $limit
     * @param string $startDate
     * @param string $endDate
     * @param int $usuario_id
     * @param string $situacao_id
     * @param string $tipo_solicitacao_id
     * @param int $userResp
     * @param array $currentUserRoles
     * @return array
     */
    public function execute(
        int $offset,
        int $limit,
        string $startDate,
        string $endDate,
        int $usuario_id,
        string $situacao_id,
        string $tipo_solicitacao_id,
        int $userResp,
        array $currentUserRoles = []
    ): array
    {
        return $this->negotiationRepository->findAll(
            $offset,
            $limit,
            $startDate,
            $endDate,
            $usuario_id,
            $situacao_id,
            $tipo_solicitacao_id,
            $userResp,
            $currentUserRoles
        );
    }
}