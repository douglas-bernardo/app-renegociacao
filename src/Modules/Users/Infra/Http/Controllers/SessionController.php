<?php

namespace App\Modules\Users\Infra\Http\Controllers;


use App\Modules\Users\Infra\Database\Entity\User;
use App\Modules\Users\Services\AuthenticateUserService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Errors\ApiException;
use App\Shared\Facades\ContainerDependenceInjection\ContainerDependenceInjection;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class SessionController
 * @package App\Modules\Users\Infra\Http\Controllers
 */
class SessionController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        $request_data = $request->toArray();

        $this->validate($request_data,  new Collection([
            'email'=> new Email(),
            'password' => [new NotBlank(), new Length(['min' => 6])],
        ]));

        Transaction::open($_ENV['APPLICATION']);

        /** @var AuthenticateUserService $authenticateUserService */
        $authenticateUserService = $this->containerBuilder->get('authenticateUser.service');
        $auth = $authenticateUserService->execute($request_data['email'], $request_data['password']);

        $user = $auth['user'];
        $user = $user->toArray();
        $token = $auth['token'];

        Transaction::close();

        return new JsonResponse([
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ]);
    }
}
