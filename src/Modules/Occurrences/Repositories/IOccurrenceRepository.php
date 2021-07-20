<?php


namespace App\Modules\Occurrences\Repositories;


use App\Modules\Occurrences\Infra\Database\Entity\Occurrence;

/**
 * Interface IOccurrenceRepository
 * @package App\Modules\Occurrences\Repositories
 */
interface IOccurrenceRepository
{
    /**
     * @param int $offset
     * @param int $limit
     * @param string $startDate
     * @param string $endDate
     * @param string $status
     * @param int $userResp
     * @param int|null $ts_usuario_id
     * @param array $userRoles
     * @return array
     */
    public function findAll(
        int $offset,
        int $limit,
        string $startDate,
        string $endDate,
        string $status,
        int $userResp,
        int $ts_usuario_id = null,
        array $userRoles = []
    ): array;

    /**
     * @param int $id
     * @return Occurrence|null
     */
    public function findById(int $id): ? Occurrence;

    /**
     * @param int $ts_usuario_id
     * @param string $param
     * @param array $userRoles
     * @return array
     */
    public function find(int $ts_usuario_id, string $param, array $userRoles = []): array;
}