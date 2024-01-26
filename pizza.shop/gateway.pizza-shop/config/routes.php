<?php
declare(strict_types=1);

use pizzashop\gateway\app\actions\ShopAction;
use pizzashop\gateway\app\middleWare\CheckAuthUser;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):void {

    //$app->add(\pizzashop\gateway\app\middleWare\Cors::class);

    $app->post('/commandes[/]', ShopAction::class)
        ->setName('creer_commande')->add(CheckAuthUser::class);

    $app->get('/commande/{id_commande}[/]', ShopAction::class)
        ->setName('commande')->add(CheckAuthUser::class);

    $app->patch("/commande/{id_commande}[/]", ShopAction::class)
        ->setName("valider")->add(CheckAuthUser::class);

    $app->get('/produits[/]', \pizzashop\gateway\app\actions\CatalogAction::class)
        ->setName('produits');

    $app->get('/produit/{id_produit}[/]', \pizzashop\gateway\app\actions\CatalogAction::class)
        ->setName('produit');

    $app->get('/categorie/{id_categorie}/produits[/]', \pizzashop\gateway\app\actions\CatalogAction::class)
        ->setName('produits_par_categorie');

    $app->post('/signup[/]', \pizzashop\gateway\app\actions\AuthAction::class)
        ->setName('signup');

    $app->post('/signin[/]', \pizzashop\gateway\app\actions\AuthAction::class)
        ->setName('signin');

    $app->get('/refresh[/]', \pizzashop\gateway\app\actions\AuthAction::class)
        ->setName('refresh');

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

};