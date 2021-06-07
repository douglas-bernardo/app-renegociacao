<?php

namespace App\Controller\Imports;

use App\Database\Transaction;
use App\Model\Product;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportProductsController
{
    public function index(): JsonResponse
    {
        try {
            Transaction::open($_ENV['APPLICATION']);

            $baseURL = $_ENV['URI_API_TIMESHARING'];

            $client = HttpClient::create();
            $response = $client->request('GET', $baseURL . '/product');
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
                    $projeto = new Product();
                    $projeto->fromArray($item);
                    $projeto->store();
                    unset($projeto);
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
