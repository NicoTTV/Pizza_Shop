<?php
declare(strict_types=1);

use pizzashop\commande\app\actions\AccederCommandeAction;
use pizzashop\commande\app\actions\CreerCommandeAction;
use pizzashop\commande\app\actions\ValiderCommandeAction;
use pizzashop\commande\app\middleWare\CheckAuthUser;
use pizzashop\commande\app\middleWare\CheckIfOwner;

return function( \Slim\App $app):void {

    $app->post('/commandes[/]', CreerCommandeAction::class)
        ->setName('creer_commande')->add(CheckAuthUser::class);

    $app->get('/commande/{id_commande}[/]', AccederCommandeAction::class)
        ->setName('commande')->add(CheckAuthUser::class);

    $app->patch("/commande/{id_commande}[/]", ValiderCommandeAction::class)
        ->setName("valider")->add(CheckIfOwner::class)->add(CheckAuthUser::class);

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
};