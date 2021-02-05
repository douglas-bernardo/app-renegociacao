<?php

namespace App\Controller;

use App\Database\Transaction;
use Exception;
use App\Model\Ocorrencia;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImportOcorrenciasController
{
    public function index()
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $baseURL = $_ENV['URI_API_TIMESHARING'] . '/ocorrencias';

            $lastOcorrenciaId = (new Ocorrencia())->getLast();
            $lastOcorrenciaObject = new Ocorrencia($lastOcorrenciaId);

            if ($lastOcorrenciaObject->toArray()) {
                $num_ocorrencia = $lastOcorrenciaObject->numero_ocorrencia;
                $url = $baseURL . "/list-after-number/{$num_ocorrencia}";
            } else {
                $url = $baseURL . "/list-after-date?date=2021-01-01";
            }

            $client = HttpClient::create();
            $response = $client->request('GET', $url);
            $ocorrencias = $response->toArray(true);

            if ($ocorrencias['status'] === 'error') {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'API' => 'An error occurred on API'
                    ]
                ]);
            }

            if ($ocorrencias['data']) {
                foreach ($ocorrencias['data'] as $arrayOcorrencia) {
                    $ocorrencia = new Ocorrencia();
                    $ocorrencia->fromArray($arrayOcorrencia);
                    $ocorrencia->situacao_id = 1;
                    $ocorrencia->store();
                    unset($ocorrencia);
                }
            }

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' =>  count($ocorrencias['data']) . ' imported.'
            ]);
        } catch (Exception $e) {
            Transaction::rollback();
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
