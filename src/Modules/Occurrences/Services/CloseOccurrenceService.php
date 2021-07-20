<?php


namespace App\Modules\Occurrences\Services;


use App\Modules\Occurrences\Repositories\IOccurrenceRepository;
use App\Shared\Errors\ApiException;
use Exception;

/**
 * Class CloseOccurrenceService
 * @package App\Modules\Occurrences\Services
 */
class CloseOccurrenceService
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
     * @param array $user
     * @throws ApiException
     * @throws Exception
     */
    public function execute(int $id, array $user): void
    {
        $occurrence = $this->occurrenceRepository->findById($id);
        if ($occurrence->status_ocorrencia_id != 1) {
            throw new ApiException("Ocorrência em negociação ou já finalizada");
        }

        if ($occurrence->idusuario_resp !== $user['ts_usuario_id']) {
            throw new ApiException("Apenas as próprias ocorrências podem ser finalizadas");
        }

        $occurrence->status_ocorrencia_id = 3;
        $occurrence->store();
    }
}