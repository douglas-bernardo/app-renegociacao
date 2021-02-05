<?php

namespace App\Controller;

use App\Database\Transaction;
use App\Model\Motivo;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImportMotivosController
{
    public function index()
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $baseURL = $_ENV['URI_API_TIMESHARING'];

            $client = HttpClient::create();
            $response = $client->request('GET', $baseURL . '/motivos');
            $result = $response->toArray(true);

            if ($result['status'] === 'error') {
                return new JsonResponse([
                    'status' => 'fail',
                    'data' => [
                        'API' => 'An error occurred on API'
                    ]
                ]);
            }

            if ($result['data']) {
                foreach ($result['data'] as $item) {
                    $motivo = new Motivo();
                    $motivo->fromArray($item);
                    $motivo->store();
                    unset($motivo);
                }
            }

            Transaction::close();

            return new JsonResponse([
                'status' => 'success',
                'data' =>  count($result['data']) . ' imported.'
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
