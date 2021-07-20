<?php


namespace App\Modules\Occurrences\Services;


use App\Modules\Occurrences\Infra\Database\Entity\Occurrence;
use App\Modules\Occurrences\Repositories\IOccurrenceRepository;
use App\Shared\Errors\ApiException;

/**
 * Class ShowOccurrenceService
 * @package App\Modules\Occurrences\Services
 */
class ShowOccurrenceService
{
    /**
     * @var IOccurrenceRepository
     */
    private IOccurrenceRepository $occurrenceRepository;

    /**
     * ShowOccurrenceService constructor.
     * @param IOccurrenceRepository $occurrenceRepository
     */
    public function __construct(IOccurrenceRepository $occurrenceRepository)
    {
        $this->occurrenceRepository = $occurrenceRepository;
    }


    /**
     * @param int $id
     * @return Occurrence
     * @throws ApiException
     */
    public function execute(int $id): Occurrence
    {
        $occurrence = $this->occurrenceRepository->findById($id);

        if (!$occurrence) {
            throw new ApiException('Ocorrência não encontrada');
        }

        return $occurrence;
    }
}