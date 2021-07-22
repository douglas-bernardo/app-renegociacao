<?php


namespace App\Modules\Negotiations\Repositories;


use App\Modules\Domain\Infra\Database\Entity\NegotiationsAnalytic;
use App\Modules\Negotiations\Infra\Database\Entity\Negotiation;

/**
 * Interface INegotiationRepository
 * @package App\Modules\Negotiations\Repositories
 */
interface INegotiationRepository
{
    /**
     * @param int $offset
     * @param int $limit
     * @param string $startDate
     * @param string $endDate
     * @param int $usuario_id
     * @param string $situacao_id
     * @param string $tipo_solicitacao_id
     * @param int $userResp
     * @param array $currentUserPermissions
     * @return array
     */
    public function findAll(
        int $offset,
        int $limit,
        string $startDate,
        string $endDate,
        int $usuario_id,
        string $situacao_id,
        string $tipo_solicitacao_id,
        int $userResp = 0,
        array $currentUserPermissions = []
    ): array;

    /**
     * @param int $id
     * @return NegotiationsAnalytic|null
     * Load negotiation from analytic view
     */
    public function findById(int $id): ?NegotiationsAnalytic;

    /**
     * @param int $ts_usuario_id
     * @param string $param
     * @param array $currentUserRoles
     * @return array
     */
    public function find(int $ts_usuario_id, string $param, array $currentUserRoles = []): array;

    /**
     * @param int $id
     * @return Negotiation|null
     * Load negotiation from active record
     */
    public function load(int $id): ?Negotiation;

    /**
     * @param int $motivo_id
     * @param int $tipo_solicitacao_id
     * @param int $origem_id
     * @param int $usuario_id
     * @param int $ocorrencia_id
     * @return Negotiation
     */
    public function create(
        int $motivo_id,
        int $tipo_solicitacao_id,
        int $origem_id,
        int $usuario_id,
        int $ocorrencia_id
    ): Negotiation;
}