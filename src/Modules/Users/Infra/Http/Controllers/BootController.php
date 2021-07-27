<?php

namespace App\Modules\Users\Infra\Http\Controllers;

use App\Modules\Users\Services\BootService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class UserController
 * @package App\Modules\Users\Infra\Http\Controllers
 */
class BootController extends AbstractController
{
    /**
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function create(): JsonResponse
    {
        Transaction::open($_ENV['APPLICATION']);

        $rolesData = [
            ["name" => "ADMIN", "description" => "Administrador"],
            ["name" => "Gerente", "description" => "Gerente"],
            ["name" => "Coordenador", "description" => "Coordenador"],
            ["name" => "Consultor", "description" => "Consultor"]
        ];

        $adminUserData = [
            "primeiro_nome" => "ADMIN",
            "nome" => "ADMIN",
            "email" => "admin@admin.com.br",
            "password" => "admin@admin.com.br"
        ];

        /** @var BootService $bootService */
        $bootService = $this->containerBuilder->get('bootService.service');
        $userAdmin = $bootService->execute($rolesData, $adminUserData)->toArray();

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'System initialization was successful. User ADMIN was created successfully.',
            'data' => $userAdmin
        ]);
    }
}
