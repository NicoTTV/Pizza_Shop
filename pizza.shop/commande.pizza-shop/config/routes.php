<?php
declare(strict_types=1);

use pizzashop\commande\app\actions\AccederCommandeAction;
use pizzashop\commande\app\actions\CreerCommandeAction;
use pizzashop\commande\app\actions\ValiderCommandeAction;
use pizzashop\commande\app\middleWare\CheckIfOwner;
use pizzashop\commande\app\actions\CommandeAuthAction;

return function( \Slim\App $app):void {

    $app->post('/commande/signin', CommandeAuthAction::class)
        ->setName('connexion_commande');

    $app->post('/commandes[/]', CreerCommandeAction::class)
        ->setName('creer_commande');

    $app->get('/commande/{id_commande}[/]', AccederCommandeAction::class)
        ->setName('commande')->add(CheckIfOwner::class);

    $app->patch("/commande/{id_commande}[/]", ValiderCommandeAction::class)
        ->setName("valider")->add(CheckIfOwner::class);

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
};