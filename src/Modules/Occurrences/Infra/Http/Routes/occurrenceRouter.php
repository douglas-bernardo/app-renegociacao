<?php

use App\Modules\Occurrences\Infra\Http\Controllers\OccurrenceCloseController;
use App\Modules\Occurrences\Infra\Http\Controllers\OccurrenceController;
use App\Modules\Occurrences\Infra\Http\Controllers\OccurrenceRegisterController;
use App\Modules\Occurrences\Infra\Http\Controllers\OccurrenceSearchController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$occurrenceRouter = new RouteCollection();

$occurrenceRouter->add(
    'occurrences',
    new Route(
        '/occurrences',
        ['_controller' => [OccurrenceController::class, 'index']],
        [], [], '', [], ['GET']
    )
);

$occurrenceRouter->add(
    'occurrences-search',
    new Route('/occurrences/search',
        ['_controller' => [OccurrenceSearchController::class, 'index']],
        [], [], '', [], ['GET']
    )
);

$occurrenceRouter->add(
    'occurrences-show',
    new Route(
        '/occurrences/{id}',
        ['_controller' => [OccurrenceController::class, 'show']],
        [], [], '', [], ['GET']
    )
);

$occurrenceRouter->add(
    'occurrences-register',
    new Route(
        '/occurrences/{occurrenceId}/register',
        ['_controller' => [OccurrenceRegisterController::class, 'create']]
    )
);

$occurrenceRouter->add(
    'occurrences-close',
    new Route(
        '/occurrences/{id}',
        ['_controller' => [OccurrenceCloseController::class, 'update']],
        [], [], '', [], ['PUT']
    )
);

return $occurrenceRouter;