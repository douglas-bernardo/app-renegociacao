<?php


namespace App\Modules\Imports\Infra\Http\Controllers;


use App\Modules\Domain\Infra\Database\Entity\Product;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;

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
                throw new ApiException('An error occurred on Timesharing API');
            }

            if ($result['data']) {
                foreach ($result['data'] as $item) {
                    $product = new Product();
                    $product->fromArray($item);
                    $product->store();
                    unset($product);
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
