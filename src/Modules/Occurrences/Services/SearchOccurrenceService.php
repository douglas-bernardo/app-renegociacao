<?php


namespace App\Modules\Occurrences\Services;


use App\Modules\Occurrences\Repositories\IOccurrenceRepository;
use App\Shared\Errors\ApiException;

/**
 * Class SearchOccurrenceService
 * @package App\Modules\Occurrences\Services
 */
class SearchOccurrenceService
{
    /**
     * @var IOccurrenceRepository
     */
    private IOccurrenceRepository $occurrenceRepository;

    /**
     * SearchOccurrenceService constructor.
     * @param IOccurrenceRepository $occurrenceRepository
     */
    public function __construct(IOccurrenceRepository $occurrenceRepository)
    {
        $this->occurrenceRepository = $occurrenceRepository;
    }

    /**
     * @param int $ts_usuario_id
     * @param string $param
     * @param array $userRoles
     * @return array
     * @throws ApiException
     */
    public function execute(int $ts_usuario_id, string $param, array $userRoles = []): array
    {
        if (empty($param)) throw new ApiException('Invalid request. Url "param" is required');
        return $this->occurrenceRepository->find($ts_usuario_id, $param, $userRoles);
    }
}