<?php

namespace pizzashop\shop\app\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class AbstractAction
{
    public abstract function __invoke (Request $request,Response $response, $args): ResponseInterface;

    protected function formaterCommande($commande): array
    {
        $data = [
            'type' => 'ressource',
            'commande' => [],
        ];

        $data['commande'][] = [
            'id' => $commande->id,
            'date_commande' => $commande->date_commande,
            'type_livraison' => $commande->type_livraison,
            'etat' => $commande->etat,
            'mail_client' => $commande->email_client,
            'montant' => $commande->montant,
            'delai' => $commande->delai,
            'items' => []
        ];

        foreach ($commande->items as $it){
            $data['commande']['items'][] = [
                'numero' => $it->numero,
                'taille' => $it->taille,
                'quantite' => $it->quantite,
                'libelle' => $it->libelle,
                'libelle_taille' => $it->libelleTaille,
                'tarif' => $it->tarif,
            ];
        }
        return $data;
    }
}