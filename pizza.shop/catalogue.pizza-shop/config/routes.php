<?php
declare(strict_types=1);

use pizzashop\catalogue\app\actions\AccederProduitAction;
use pizzashop\catalogue\app\actions\ListerProduitsAction;
use pizzashop\catalogue\app\actions\ListerProduitsParCategorieAction;

return function(\Slim\App $app):void {

    $app->get('/produits[/]', ListerProduitsAction::class)
        ->setName('produits');

    $app->get('/produit/{id_produit}[/]', AccederProduitAction::class)
        ->setName('produit');

    $app->get('/categorie/{id_categorie}/produits[/]', ListerProduitsParCategorieAction::class)
        ->setName('produits_par_categorie');

    $app->get('/produits/search[/]', ListerProduitsAction::class)
        ->setName('produit_search');

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
};