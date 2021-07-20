<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$importsRouter = new RouteCollection();
$namespacePrefix = 'App\Modules\Imports\Infra\Http\Controllers\\';

$importOccurrences = new Route('/occurrences', [
    '_controller' => $namespacePrefix . 'ImportOccurrencesController::index'
]);

$importProducts = new Route('/products', [
    '_controller' => $namespacePrefix . 'ImportProductsController::index'
]);

$importsRouter->add('import-occurrences', $importOccurrences);
$importsRouter->add('import-products', $importProducts);

$importsRouter->addPrefix('/import');
return $importsRouter;