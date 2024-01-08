<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):void {

    $app->post('/commandes[/]', \pizzashop\shop\app\actions\CreerCommandeAction::class)
        ->setName('creer_commande');

    $app->get('/commandes/{id_commande}[/]', \pizzashop\shop\app\actions\AccederCommandeAction::class)
        ->setName('commande');

    $app->patch("/commandes/{id_commande}[/]", \pizzashop\shop\app\actions\ValiderCommandeAction::class)
        ->setName("valider");

    $app->get('/produits[/]', \pizzashop\shop\app\actions\ListerProduitsAction::class)
        ->setName('produits');

    $app->get('/produits/{id_produit}[/]', \pizzashop\shop\app\actions\AccederProduitAction::class)
        ->setName('produit');

    $app->get('/categories/{id_categorie}/produits[/]', \pizzashop\shop\app\actions\ListerProduitsParCategorieAction::class)
        ->setName('produits_par_categorie');

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
};