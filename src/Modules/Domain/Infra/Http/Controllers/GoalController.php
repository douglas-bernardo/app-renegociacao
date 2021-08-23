<?php

namespace App\Modules\Domain\Infra\Http\Controllers;

use App\Modules\Domain\Services\CreateGoalService;
use App\Modules\Domain\Services\ListGoalsService;
use App\Modules\Domain\Services\ShowGoalService;
use App\Modules\Domain\Services\UpdateGoalService;
use App\Shared\Bundle\Controller\AbstractController;
use App\Shared\Bundle\Controller\TokenAuthenticatedController;
use App\Shared\Errors\ApiException;
use App\Shared\Infra\Database\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class GoalController extends AbstractController implements TokenAuthenticatedController
{
    /**
     * @throws ApiException
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->attributes->get('user');

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager->getAuthorizations($user['uid'])->is(['ROLE_ADMIN']);

        /** @var ListGoalsService $listGoalsService */
        $listGoalsService = $this->containerBuilder->get('listGoals.service');
        $goals = $listGoalsService->execute();

        Transaction::close();
        return new JsonResponse([
            'status' => 'success',
            'data' => $goals
        ], 200, ['x-total-count' => count($goals)]);
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        $user = $request->attributes->get('user');
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        $constraint = new Collection([
            'goal_type_id' => new NotBlank(),
            'current_year' => new NotBlank()
        ]);
        $constraint->allowExtraFields = true;
        $this->validate($request_data, $constraint);

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR'])
            ->can('configuracoesMetasCriar');

        /** @var CreateGoalService $createGoalService */
        $createGoalService = $this->containerBuilder->get('createGoal.service');
        $goal = $createGoalService->execute($request_data)->toArray();

        Transaction::close();
        return new JsonResponse(['status' => 'success', 'data' => $goal]);
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('user');
        $request_data = $request->toArray();
        $request_data = filter_var_array($request_data, FILTER_SANITIZE_STRING);

        Transaction::open($_ENV['APPLICATION']);
        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR'])
            ->can('configuracoesMetasEditar');

        /** @var UpdateGoalService $updateGoalService */
        $updateGoalService = $this->containerBuilder->get('updateGoal.service');
        $goal = $updateGoalService->execute($request_data, $id)->toArray();

        Transaction::close();

        return new JsonResponse(['status' => 'success', 'data' => $goal]);
    }

    /**
     * @throws ApiException
     * @throws Exception
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('user');
        Transaction::open($_ENV['APPLICATION']);

        $this->authorizationManager
            ->getAuthorizations($user['uid'])
            ->is(['ROLE_ADMIN', 'ROLE_GERENTE', 'ROLE_COORDENADOR'])
            ->can('configuracoesMetasVer');

        /** @var ShowGoalService $showGoalService */
        $showGoalService = $this->containerBuilder->get('showGoal.service');
        $goal = $showGoalService->execute((int)$id)->toArray();

        Transaction::close();

        return new JsonResponse(['status' => 'success', 'data' => $goal]);
    }
}