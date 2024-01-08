<?php
declare(strict_types=1);

use pizzashop\shop\app\actions\commandes\AccederCommandeAction;
use pizzashop\shop\app\actions\commandes\CreerCommandeAction;
use pizzashop\shop\app\actions\commandes\ValiderCommandeAction;
use pizzashop\shop\app\middleWare\CheckAuthUser;
use pizzashop\shop\app\middleWare\CheckIfOwner;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):void {

    $app->post('/commandes[/]', CreerCommandeAction::class)
        ->setName('creer_commande')->add(CheckAuthUser::class);

    $app->get('/commandes/{id_commande}[/]', AccederCommandeAction::class)
        ->setName('commande')->add(CheckIfOwner::class)->add(CheckAuthUser::class);

    $app->patch("/commandes/{id_commande}[/]", ValiderCommandeAction::class)
        ->setName("valider")->add(CheckIfOwner::class)->add(CheckAuthUser::class);

    $app->get('/produits[/]', \pizzashop\shop\app\actions\ListerProduitsAction::class)
        ->setName('produits');

    $app->get('/produits/{id_produit}[/]', \pizzashop\shop\app\actions\AccederProduitAction::class)
        ->setName('produit');

    $app->get('/categories/{id_categorie}/produits[/]', \pizzashop\shop\app\actions\ListerProduitsParCategorieAction::class)
        ->setName('produits_par_categorie');

    $app->get('/produits[/]', \pizzashop\shop\app\actions\ListerProduitsAction::class)
        ->setName('produits');

    $app->get('/produits/{id_produit}[/]', \pizzashop\shop\app\actions\AccederProduitAction::class)
        ->setName('produit');

    $app->get('/categories/{id_categorie}/produits[/]', \pizzashop\shop\app\actions\ListerProduitsParCategorieAction::class)
        ->setName('produits_par_categorie');


    $app->get('/test', function (Request $request, Response $response) {
        $response->getBody()->write("Hello World");
        return $response->withHeader('Content-Type', 'text/plain');
    });

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
};