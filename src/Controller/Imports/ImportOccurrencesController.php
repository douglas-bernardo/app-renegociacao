<?php

namespace App\Controller\Imports;

use App\Database\Transaction;
use App\Log\LoggerTXT;
use Exception;
use App\Model\Occurrence;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImportOccurrencesController
{
    public function index(): JsonResponse
    {
        try {
            Transaction::open($_ENV['APPLICATION']);
            Transaction::setLogger(new LoggerTXT(__DIR__ . '/../../../tmp/import_occurrence.log'));

            $baseURL = $_ENV['URI_API_TIMESHARING'] . '/occurrences';

            $lastOcorrenciaId = (new Occurrence())->getLast();
            $lastOcorrenciaObject = new Occurrence($lastOcorrenciaId);

            if ($lastOcorrenciaObject->toArray()) {
                $lastOccurrence = $lastOcorrenciaObject->numero_ocorrencia;
                $url = $baseURL . "/list-recent-occurrences-by-number/{$lastOccurrence}";
            } else {
                $url = $baseURL . "/list-recent-occurrences-by-date?date=2021-01-01";
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
                    $ocorrencia = new Occurrence();
                    $ocorrencia->fromArray($arrayOcorrencia);
                    $ocorrencia->status_ocorrencia_id = 1;
                    $ocorrencia->store();
                    unset($ocorrencia);
                }
            }

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' => count($ocorrencias['data']) . ' imported.'
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
