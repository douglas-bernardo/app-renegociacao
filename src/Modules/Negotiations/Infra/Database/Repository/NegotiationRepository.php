<?php


namespace App\Modules\Negotiations\Infra\Database\Repository;


use App\Modules\Domain\Infra\Database\Entity\NegotiationsAnalytic;
use App\Modules\Negotiations\Infra\Database\Entity\Negotiation;
use App\Modules\Negotiations\Repositories\INegotiationRepository;
use App\Shared\Infra\Database\Criteria;
use App\Shared\Infra\Database\Expression;
use App\Shared\Infra\Database\Filter;
use App\Shared\Infra\Database\Repository;
use Exception;

/**
 * Class NegotiationRepository
 * @package App\Modules\Negotiations\Infra\Database\Repository
 */
class NegotiationRepository implements INegotiationRepository
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
     * @param array $currentUserRoles
     * @return array
     * @throws Exception
     */
    public function findAll(
        int $offset,
        int $limit,
        string $startDate,
        string $endDate,
        int $usuario_id,
        string $situacao_id = '0',
        string $tipo_solicitacao_id = '0',
        int $userResp = 0,
        array $currentUserRoles = []
    ): array
    {
        $criteria = new Criteria();
        $criteria->setProperty('offset', $offset);
        $criteria->setProperty('limit', $limit);
        $criteria->setProperty('order', 'data_ocorrencia DESC');

        $criteria->add(new Filter('data_ocorrencia', '>=', $startDate));
        $criteria->add(new Filter('data_ocorrencia', '<=', $endDate));

        if (!in_array('ROLE_ADMIN', $currentUserRoles)) {
            $criteria->add(new Filter('usuario_id', '=', $usuario_id));
        }

        if ($situacao_id !== '0') {
            $options = explode(",", $situacao_id);
            $criteria->add(new Filter('situacao_id', 'IN', $options));
        }

        if ($tipo_solicitacao_id !== '0') {
            $options = explode(",", $tipo_solicitacao_id);
            $criteria->add(new Filter('tipo_solicitacao_id', 'IN', $options));
        }

        if (in_array('ROLE_ADMIN', $currentUserRoles) && isset($userResp) && $userResp !== 0) {
            $criteria->add(new Filter('usuario_id', '=', $userResp));
        }

        $repository = new Repository(NegotiationsAnalytic::class, true);
        $negotiations = $repository->load($criteria);
        $result = array();
        if ($negotiations) foreach ($negotiations as $negotiation) {
            $result[] = $negotiation->toArray();
        }

        $criteria->resetProperties();
        $count = $repository->count($criteria);
        return ['negotiations' => $result, 'total' => $count];
    }

    /**
     * @param int $motivo_id
     * @param int $tipo_solicitacao_id
     * @param int $origem_id
     * @param int $usuario_id
     * @param int $ocorrencia_id
     * @return Negotiation
     * @throws Exception
     */
    public function create(
        int $motivo_id,
        int $tipo_solicitacao_id,
        int $origem_id,
        int $usuario_id,
        int $ocorrencia_id): Negotiation
    {
        $negotiation = new Negotiation();
        $negotiation->motivo_id = $motivo_id;
        $negotiation->tipo_solicitacao_id = $tipo_solicitacao_id;
        $negotiation->origem_id = $origem_id;
        $negotiation->usuario_id = $usuario_id;
        $negotiation->ocorrencia_id = $ocorrencia_id;
        $negotiation->situacao_id = 1;
        $negotiation->store();

        return $negotiation;
    }

    /**
     * @param int $id
     * @return NegotiationsAnalytic|null
     * @throws Exception
     */
    public function findById(int $id): ?NegotiationsAnalytic
    {
        $criteria = new Criteria();
        $criteria->add(new Filter('id', '=', $id));
        $repository = new Repository(NegotiationsAnalytic::class, true);
        $result = $repository->load($criteria);
        return $result ? $result[0] : null;
    }

    /**
     * @param int $id
     * @return Negotiation|null
     * @throws Exception
     */
    public function load(int $id): ?Negotiation
    {
        $criteria = new Criteria();
        $criteria->add(new Filter('id', '=', $id));
        $repository = new Repository(Negotiation::class);
        $result = $repository->load($criteria);
        return $result ? $result[0] : null;
    }

    /**
     * @param int $ts_usuario_id
     * @param string $param
     * @param array $currentUserRoles
     * @return array
     * @throws Exception
     */
    public function find(int $ts_usuario_id, string $param, array $currentUserRoles = []): array
    {
        $repository = new Repository(NegotiationsAnalytic::class, true);
        $mainCriteria = new Criteria();
        $criteria1 = new Criteria();

        $filter1 = new Filter('numero_ocorrencia', '=', $param);
        $filter2 = new Filter('nome_cliente', 'LIKE', $param . '%');
        $filter3 = new Filter('concat(numeroprojeto, numerocontrato)', '=', $param);

        $criteria1->add($filter1, Expression::OR_OPERATOR);
        $criteria1->add($filter2, Expression::OR_OPERATOR);
        $criteria1->add($filter3, Expression::OR_OPERATOR);
        $mainCriteria->add($criteria1);

        if (!in_array('ROLE_ADMIN', $currentUserRoles)) {
            $criteria2 = new Criteria();
            $criteria2->add(new Filter('id_usuario_resp_ts', '=', $ts_usuario_id));
            $mainCriteria->add($criteria2);
        }

        $negotiations = $repository->load($mainCriteria);
        $result = array();
        if ($negotiations) {
            foreach ($negotiations as $negotiation) {
                $result[] = $negotiation->toArray();
            }
        }

        $mainCriteria->resetProperties();
        $count = $repository->count($mainCriteria);

        return ['negotiations' => $result, 'total' => $count];
    }
}