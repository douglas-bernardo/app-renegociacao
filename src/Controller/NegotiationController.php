<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Expression;
use App\Database\Filter;
use App\Database\Repository;
use App\Database\Transaction;
use App\Log\LoggerTXT;
use App\Model\CancelContract;
use App\Model\Negotiation;
use App\Model\NegotiationsAnalytic;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NegotiationController
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            $criteria = new Criteria;
            $offset = $query['offset'] ?? 0;
            $limit = $query['limit'] ?? 10;

            $criteria->setProperty('offset', $offset);
            $criteria->setProperty('limit', $limit);
            $criteria->setProperty('order', 'data_ocorrencia DESC');

            $startDate = $query['startDate'] ?? date('Y-m-d');
            $endDate = $query['endDate'] ?? date('Y-m-d');

            $criteria->add(
                new Filter('id_usuario_resp_ts', '=', $user['ts_usuario_id'])
            );
            $criteria->add(new Filter('data_ocorrencia', '>=', $startDate));
            $criteria->add(new Filter('data_ocorrencia', '<=', $endDate));

            if (isset($query['situacao_id']) && $query['situacao_id'] !== '0') {
                $situation = explode(",", $query['situacao_id']);
                $criteria->add(new Filter('situacao_id', 'IN', $situation));
            }

            if (isset($query['tipo_solicitacao_id']) && $query['tipo_solicitacao_id'] !== '0') {
                $tipoSolicitacaoId = explode(",", $query['tipo_solicitacao_id']);
                $criteria->add(new Filter('tipo_solicitacao_id', 'IN', $tipoSolicitacaoId));
            }

            Transaction::open($_ENV['APPLICATION']);
            Transaction::setLogger(new LoggerTXT(__DIR__ . '/../../tmp/negotiation.log'));

            $repository = new Repository(NegotiationsAnalytic::class, true);
            $negotiations = $repository->load($criteria);

            $result = array();
            if ($negotiations) foreach ($negotiations as $negotiation) {
                $result[] = $negotiation->toArray();
            }

            $criteria->resetProperties();
            $count = $repository->count($criteria);

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => $result
            ], 200, ['x-total-count' => $count]);

        } catch (Exception $exception) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            if (!isset($id)) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'parameter is required'
                ], 400);
            }

            Transaction::open($_ENV['APPLICATION']);

            $criteria = new Criteria();
            $criteria->add(new Filter('id', '=', $id));

            $repository = new Repository(NegotiationsAnalytic::class, true);
            $result = $repository->load($criteria);

            $negotiation = $result ? $result[0]->toArray() : null;

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => $negotiation
            ]);
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $request_data = $request->toArray();
            $request_data = filter_var_array(
                $request_data,
                FILTER_SANITIZE_STRING
            );

            if (!isset($request_data['negotiation'])) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'request' => 'invalid request'
                    ]
                ], 400);
            }

            $negotiation_data = $request_data['negotiation'];
            if (
                !isset($negotiation_data['motivo_id']) ||
                !isset($negotiation_data['tipo_solicitacao_id']) ||
                !isset($negotiation_data['origem_id'])
            ) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'request' => 'invalid request'
                    ]
                ], 400);
            }

            Transaction::open($_ENV['APPLICATION']);
            $negotiation = new Negotiation($id);
            if (!$negotiation->id) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'negotiation' => 'negotiation not found'
                    ]
                ], 404);
            }

            if ($negotiation->usuario_id !== $user['uid']) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'negotiation' => 'only own negotiations can be edited'
                    ]
                ], 403);
            }

            $negotiation->motivo_id = $negotiation_data['motivo_id'];
            $negotiation->tipo_solicitacao_id = $negotiation_data['tipo_solicitacao_id'];
            $negotiation->origem_id = $negotiation_data['origem_id'];

            $negotiation->reembolso = isset($negotiation_data['reembolso']) ? str_format_currency($negotiation_data['reembolso']) : null;
            $negotiation->numero_pc = $negotiation_data['numero_pc'] ?? null;
            $negotiation->taxas_extras = isset($negotiation_data['taxas_extras']) ? str_format_currency($negotiation_data['taxas_extras']) : null;
            $negotiation->valor_primeira_parcela = isset($negotiation_data['valor_primeira_parcela']) ? str_format_currency($negotiation_data['valor_primeira_parcela']) : null;

            if (isset($negotiation_data['data_finalizacao'])) {
                $negotiation->data_finalizacao = $dateFin = date("Y-m-d", strtotime($negotiation_data['data_finalizacao']));
            }

            if ((int) $negotiation->situacao_id === 2) {
                $cancelContract = (new CancelContract())->loadBy('negociacao_id', $negotiation->id);
                if ($cancelContract) {
                    $cancelContract->multa = isset($negotiation_data['multa']) ? str_format_currency($negotiation_data['multa']) : null;
                    $cancelContract->store();
                }
            }

            $negotiation->store();
            $negotiation = $negotiation->toArray();

            Transaction::close();
            return new JsonResponse([
                'status' => 'success',
                'data' => $negotiation
            ]);

        } catch (Exception $e) {
            Transaction::rollback();
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            $query = $request->query->all();
            $query = filter_var_array($query, FILTER_SANITIZE_STRING);

            if (!isset($query['param'])) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Request' => 'Invalid request'
                    ]
                ], 400);
            }

            Transaction::open($_ENV['APPLICATION']);
            Transaction::setLogger(new LoggerTXT(__DIR__ . '/../../tmp/search.log'));

            $param = trim($query['param']);
            $repository = new Repository(NegotiationsAnalytic::class, true);
            $criteria1 = new Criteria();

            $filter1 = new Filter('numero_ocorrencia', '=', $param);
            $filter2 = new Filter('nome_cliente', 'LIKE', $param . '%');
            $filter3 = new Filter('concat(numeroprojeto, numerocontrato)', '=', $param);

            $criteria1->add($filter1, Expression::OR_OPERATOR);
            $criteria1->add($filter2, Expression::OR_OPERATOR);
            $criteria1->add($filter3, Expression::OR_OPERATOR);

            $criteria2 = new Criteria();
            $criteria2->add(new Filter('id_usuario_resp_ts', '=', $user['ts_usuario_id']));

            $criteria = new Criteria();
            $criteria->add($criteria1);
            $criteria->add($criteria2);
            $negotiations = $repository->load($criteria);

            $result = array();
            if ($negotiations) {
                foreach ($negotiations as $negotiation) {
                    $result[] = $negotiation->toArray();
                }
            }

            $criteria->resetProperties();
            $count = $repository->count($criteria);

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => $result
            ], 200, ['x-total-count' => $count]);

        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
