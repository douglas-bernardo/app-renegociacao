<?php

namespace App\Controller;

use App\Database\Criteria;
use App\Database\Expression;
use App\Database\Filter;
use App\Database\Repository;
use App\Database\Transaction;
use App\Log\LoggerTXT;
use App\Model\Occurrence;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OccurrenceController
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
            $criteria->setProperty('order', 'dtocorrencia DESC');

            $startDate = $query['startDate'] ?? date('Y-m-d');
            $endDate = $query['endDate'] ?? date('Y-m-d');

            $criteria->add(
                new Filter('idusuario_resp', '=', $user['ts_usuario_id'])
            );
            $criteria->add(new Filter('dtocorrencia', '>=', $startDate));
            $criteria->add(new Filter('dtocorrencia', '<=', $endDate));

            if (isset($query['status']) && $query['status'] !== '0') {
                $statusOcorrenciaId = explode(",", $query['status']);
                $criteria->add(new Filter('status_ocorrencia_id', 'IN', $statusOcorrenciaId));
            }

            Transaction::open($_ENV['APPLICATION']);
            Transaction::setLogger(new LoggerTXT(__DIR__ . '/../../tmp/ocorrencias.log'));

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

            return new JsonResponse([
                'status' => 'success',
                'data' => $result
            ], 200, ['x-total-count' => $count]);

            Transaction::close();
        } catch (Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $repository = new Repository('App\Model\Occurrence');
            $criteria = new Criteria;
            $criteria->add(new Filter('id', '=', $id));
            $result = $repository->load($criteria);

            $occurrence = null;
            if ($result) {
                $occurrence = $result[0]->toArray();
            }

            return new JsonResponse([
                'status' => 'success',
                'data' => $occurrence
            ]);

            Transaction::close();
        } catch (Exception $e) {
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
            $repository = new Repository(Occurrence::class);
            $criteria1 = new Criteria();

            $filter1 = new Filter('numero_ocorrencia', '=', $param);
            $filter2 = new Filter('nome_cliente', 'LIKE', $param . '%');
            $filter3 = new Filter('concat(numeroprojeto, numerocontrato)', '=', $param);

            $criteria1->add($filter1, Expression::OR_OPERATOR);
            $criteria1->add($filter2, Expression::OR_OPERATOR);
            $criteria1->add($filter3, Expression::OR_OPERATOR);

            $criteria2 = new Criteria();
            $criteria2->add(new Filter('idusuario_resp', '=', $user['ts_usuario_id']));

            $criteria = new Criteria();
            $criteria->add($criteria1);
            $criteria->add($criteria2);
            $occurrences = $repository->load($criteria);
            $result = array();
            if ($occurrences) {
                foreach ($occurrences as $occurrence) {
                    $result[] = $occurrence->toArray();
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

    public function closeOccurrence(Request $request, string $occurrenceId): JsonResponse
    {
        try {
            $user = $request->attributes->get('user');
            Transaction::open($_ENV['APPLICATION']);

            $occurrence = new Occurrence($occurrenceId);
            if ($occurrence->status_ocorrencia_id != 1) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Occurrence' => 'Occurrence on negotiation or already finalized'
                    ]
                ], 400);
            }

            if ($occurrence->idusuario_resp !== $user['ts_usuario_id']) {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'Occurrence' => 'Only own occurrences can be finalized'
                    ]
                ], 403);
            }

            $occurrence->status_ocorrencia_id = 4;
            $occurrence->store();

            Transaction::close();

            return new JsonResponse(['status' => 'success'],202);

        } catch (Exception $e) {
            Transaction::rollback();
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
