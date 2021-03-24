<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

/**
 * Authentication
 */

$routes->add('authentication', new Route(
    '/sessions',
    ['_controller' => 'App\Controller\SessionController::create'],
    [],[],'',[],['POST']
));


/**
 * Dashboard
 */

$routes->add('dashboard', new Route('/', [
    '_controller' => 'App\Controller\DashboardController::index'
]));


/**
 * Usuarios
 */

$routes->add('users_list', new Route(
    '/users',
    ['_controller' => 'App\Controller\UserController::index'],
    [],[],'',[],['GET']
));

$routes->add('users_create', new Route(
    '/users',
    ['_controller' => 'App\Controller\UserController::create'],
    [],[],'',[],['POST']
));


/**
 * Import
 */

$routes->add('import_ocorrencias', new Route('/import/ocorrencias', [
    '_controller' => 'App\Controller\ImportOcorrenciasController::index'
]));

$routes->add('import_motivos', new Route('/import/motivos', [
    '_controller' => 'App\Controller\ImportMotivosController::index'
]));

$routes->add('import_projetos', new Route('/import/projetos', [
    '_controller' => 'App\Controller\ImportProjetosController::index'
]));


/**
 * Ocorrencia
 */

$routes->add('ocorrencias', new Route('/ocorrencias', [
    '_controller' => 'App\Controller\OcorrenciaController::index'
]));

$routes->add('ocorrencias_show', new Route('/ocorrencias/{ocorrenciaId}', [
    '_controller' => 'App\Controller\OcorrenciaController::show'
]));


/**
 * Dominio app
 */
$routes->add('situacao', new Route('/dominio/situacao', [
    '_controller' => 'App\Controller\SituacaoController::index'
]));

$routes->add('motivos', new Route('/dominio/motivos', [
    '_controller' => 'App\Controller\MotivoController::index'
]));

$routes->add('tipo-solicitacao', new Route('/dominio/tipo-solicitacao', [
    '_controller' => 'App\Controller\TipoSolicitacaoController::index'
]));

$routes->add('origem', new Route('/dominio/origem', [
    '_controller' => 'App\Controller\OrigemController::index'
]));

$routes->add('tipo-contato', new Route('/dominio/tipo-contato', [
    '_controller' => 'App\Controller\TipoContatoController::index'
]));

$routes->add('projeto', new Route('/dominio/projeto', [
    '_controller' => 'App\Controller\ProjetoController::index'
]));

/**
 * Atendimento
 */

$routes->add('atendimento_list', new Route(
    '/atendimento',
    ['_controller' => 'App\Controller\AtendimentoController::index'],
    [],[],'',[],['GET']
));

$routes->add('atendimento', new Route(
    '/atendimento',
    ['_controller' => 'App\Controller\AtendimentoController::create'],
    [],[],'',[],['POST']
));

/**
 * Negociacao
 */

$routes->add('negociacao_list', new Route(
    '/negociacao',
    ['_controller' => 'App\Controller\NegociacaoController::index'],
    [],[],'',[],['GET']
));

$routes->add('FinalizaOcorrenciaPadrao', new Route(
    '/ocorrencias/{ocorrenciaId}/finaliza-padrao', 
    ['_controller' => 'App\Controller\FinalizaOcorrenciaPadraoController::create'],
    [],[],'',[],['POST']
));

$routes->add('FinalizaOcorrenciaRetencao', new Route(
    '/ocorrencias/{ocorrenciaId}/finaliza-retencao', 
    ['_controller' => 'App\Controller\FinalizaOcorrenciaRetencaoController::create'],
    [],[],'',[],['POST']
));

$routes->add('FinalizaOcorrenciaReversao', new Route(
    '/ocorrencias/{ocorrenciaId}/finaliza-reversao', 
    ['_controller' => 'App\Controller\FinalizaOcorrenciaReversaoController::create'],
    [],[],'',[],['POST']
));

$routes->add('FinalizaOcorrenciaCancelamento', new Route(
    '/ocorrencias/{ocorrenciaId}/finaliza-cancelamento', 
    ['_controller' => 'App\Controller\FinalizaOcorrenciaCancelamentoController::create'],
    [],[],'',[],['POST']
));


return $routes;