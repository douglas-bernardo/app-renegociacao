<?php


namespace App\Modules\Occurrences\Services;


use App\Modules\Occurrences\Repositories\IOccurrenceRepository;

/**
 * Class ListOccurrenceService
 * @package App\Modules\Occurrences\Services
 */
class ListOccurrenceService
{
    /**
     * @var IOccurrenceRepository
     */
    private IOccurrenceRepository $occurrenceRepository;

    /**
     * ListOccurrenceService constructor.
     * @param IOccurrenceRepository $occurrenceRepository
     */
    public function __construct(IOccurrenceRepository $occurrenceRepository)
    {
        $this->occurrenceRepository = $occurrenceRepository;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param string $startDate
     * @param string $endDate
     * @param int|null $ts_usuario_id
     * @param string $status
     * @param int $userResp
     * @param array $userRoles
     * @return array
     */
    public function execute(
        int $offset,
        int $limit,
        string $startDate,
        string $endDate,
        string $status,
        int $userResp,
        int $ts_usuario_id = null,
        array $userRoles = []
    ): array
    {
        return $this->occurrenceRepository->findAll(
            $offset,
            $limit,
            $startDate,
            $endDate,
            $status,
            $userResp,
            $ts_usuario_id,
            $userRoles
        );
    }
}