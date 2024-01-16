<?php

namespace pizzashop\commande\app\actions;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

abstract class AbstractAction
{
    public abstract function __invoke (Request $request,Response $response, $args): ResponseInterface;

    protected function formaterCommande($commande, $request): array
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $data = [
            'type' => 'ressource',
            'commande' => [
                'id' => $commande->id,
                'date_commande' => $commande->date_commande,
                'type_livraison' => $commande->type_livraison,
                'etat' => $commande->etat,
                'mail_client' => $commande->email_client,
                'montant' => $commande->montant,
                'delai' => $commande->delai,
                'items' => []
            ],
            "links" => [
                'self' => ['href'=> $routeParser->urlFor('commande', ['id_commande' => $commande->id])],
                'valider'=> ['href'=> $routeParser->urlFor('valider', ['id_commande'=>$commande->id])]
            ]
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