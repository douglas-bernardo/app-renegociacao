<?php


namespace App\Modules\Users\Infra\Http\Controllers;


use App\Modules\Users\Services\ResetPasswordService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('user');
        Transaction::open($_ENV['APPLICATION']);

        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR'])
            ->can('configuracoesUsuariosResetDeSenha');

        /** @var ResetPasswordService $resetPasswordService */
        $resetPasswordService = $this->containerBuilder->get('resetPassword.service');
        $resetPasswordService->execute((int)$id);

        Transaction::close();
        return new JsonResponse(['status' => 'success'], 201);
    }
}