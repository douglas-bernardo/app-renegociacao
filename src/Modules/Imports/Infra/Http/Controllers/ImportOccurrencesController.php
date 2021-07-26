<?php


namespace App\Modules\Imports\Infra\Http\Controllers;


use App\Modules\Occurrences\Infra\Database\Entity\Occurrence;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImportOccurrencesController
{
    public function index(): JsonResponse
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $baseURL = $_ENV['URI_API_TIMESHARING'] . '/occurrences';

            $lastOcorrenciaId = (new Occurrence())->getLast();
            if ($lastOcorrenciaId > 0) {
                $lastOcorrenciaObject = new Occurrence($lastOcorrenciaId);
                $lastOccurrence = $lastOcorrenciaObject->numero_ocorrencia;
                $url = $baseURL . "/list-recent-occurrences-by-number/{$lastOccurrence}";
            } else {
                $url = $baseURL . "/list-recent-occurrences-by-date?date=2021-01-01";
            }

            $client = HttpClient::create();
            $response = $client->request('GET', $url);
            $ocorrencias = $response->toArray(true);

            if ($ocorrencias['status'] === 'error') {
                throw new ApiException('An error occurred on Timesharing API');
            }

            if ($ocorrencias['data']) {
                foreach ($ocorrencias['data'] as $arrayOcorrencia) {
                    $occurrence = new Occurrence();
                    $occurrence->fromArray($arrayOcorrencia);
                    $occurrence->status_ocorrencia_id = 1;
                    $occurrence->store();
                    unset($occurrence);
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
