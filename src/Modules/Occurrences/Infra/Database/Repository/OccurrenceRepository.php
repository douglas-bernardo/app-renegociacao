<?php


namespace App\Modules\Occurrences\Infra\Database\Repository;


use App\Modules\Occurrences\Infra\Database\Entity\Occurrence;
use App\Modules\Occurrences\Repositories\IOccurrenceRepository;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Expression;
use App\Shared\Infra\Database\Filter;
use App\Shared\Infra\Database\Repository;
use Exception;

/**
 * Class OccurrenceRepository
 * @package App\Modules\Occurrences\Infra\Database\Repository
 */
class OccurrenceRepository implements IOccurrenceRepository
{

    /**
     * @throws Exception
     */
    public function findAll(
        int $offset,
        int $limit,
        string $startDate,
        string $endDate,
        string $status = '0',
        int $userResp = 0,
        int $ts_usuario_id = null,
        array $userPermissions = []
    ): array
    {
        $criteria = new Criteria();
        $criteria->setProperty('offset', $offset);
        $criteria->setProperty('limit', $limit);
        $criteria->setProperty('order', 'dtocorrencia DESC');

        $criteria->add(new Filter('dtocorrencia', '>=', $startDate));
        $criteria->add(new Filter('dtocorrencia', '<=', $endDate));

        if (
            !in_array('ocorrenciasFiltrarPorResp', $userPermissions) &&
            isset($ts_usuario_id))
        {
            $criteria->add(new Filter('idusuario_resp', '=', $ts_usuario_id));
        }

        if (isset($status) && $status !== '0') {
            $statusOccurrence = explode(",", $status);
            $criteria->add(
                new Filter('status_ocorrencia_id', 'IN', $statusOccurrence)
            );
        }

        if (
            in_array('ocorrenciasFiltrarPorResp', $userPermissions)
            && isset($userResp)
            && $userResp !== 0
        ) {
            $criteria->add(new Filter('idusuario_resp', '=', $userResp));
        }

        $repository = new Repository(Occurrence::class);
        $occurrences = $repository->load($criteria);
        $result = array();
        if ($occurrences) {
            foreach ($occurrences as $occurrence) {
                $result[] = $occurrence->toArray();
            }
        }

        $criteria->resetProperties();
        $count = $repository->count($criteria);

        return ['occurrences' => $result, 'total' => $count];
    }

    /**
     * @param int $id
     * @return Occurrence|null
     * @throws Exception
     */
    public function findById(int $id): ?Occurrence
    {
        $criteria = new Criteria();
        $criteria->add(new Filter('id', '=', $id));
        $repository = new Repository(Occurrence::class);
        $result = $repository->load($criteria);
        return $result ? $result[0] : null;
    }

    /**
     * @param int $ts_usuario_id
     * @param string $param
     * @param array $userRoles
     * @return array
     * @throws Exception
     */
    public function find(int $ts_usuario_id, string $param, array $userRoles = []): array
    {
        $repository = new Repository(Occurrence::class);
        $criteria1 = new Criteria();

        $filter1 = new Filter('numero_ocorrencia', '=', $param);
        $filter2 = new Filter('nome_cliente', 'LIKE', $param . '%');
        $filter3 = new Filter('concat(numeroprojeto, numerocontrato)', '=', $param);

        $criteria1->add($filter1, Expression::OR_OPERATOR);
        $criteria1->add($filter2, Expression::OR_OPERATOR);
        $criteria1->add($filter3, Expression::OR_OPERATOR);

        $criteria = new Criteria();
        $criteria->add($criteria1);

        if (!in_array('ROLE_ADMIN', $userRoles)) {
            $criteria2 = new Criteria();
            $criteria2->add(new Filter('idusuario_resp', '=', $ts_usuario_id));
            $criteria->add($criteria2);
        }

        $occurrences = $repository->load($criteria);
        $result = array();
        if ($occurrences) {
            foreach ($occurrences as $occurrence) {
                $result[] = $occurrence->toArray();
            }
        }

        $criteria->resetProperties();
        $count = $repository->count($criteria);

        return ['occurrences' => $result, 'total' => $count];
    }
}