<?php


namespace App\Modules\Negotiations\Services;


use App\Modules\Negotiations\Repositories\INegotiationRepository;
use App\Shared\Errors\ApiException;

class SearchNegotiationService
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
     * @param int $ts_usuario_id
     * @param string $param
     * @param array $currentUserRoles
     * @return array
     * @throws ApiException
     */
    public function execute(int $ts_usuario_id, string $param, array $currentUserRoles = []): array
    {
        if (empty($param)) throw new ApiException('Invalid request. Url "param" is required');
        return $this->negotiationRepository->find($ts_usuario_id, $param, $currentUserRoles);
    }
}