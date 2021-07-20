<?php


namespace App\Modules\Domain\Infra\Http\Controllers;


use App\Modules\Domain\Services\DomainServices;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @throws ApiException
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        Transaction::open($_ENV['APPLICATION']);

        /** @var DomainServices $domainServices */
        $domainServices = $this->containerBuilder->get('domain.service');
        $result = $domainServices->execute('Product');

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'data' => $result
        ], 200, ['x-total-count' => count($result)]);
    }
}