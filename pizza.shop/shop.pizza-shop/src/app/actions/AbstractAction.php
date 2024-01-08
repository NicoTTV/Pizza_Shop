<?php

namespace pizzashop\shop\app\actions;

use Psr\Container\ContainerInterface;
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

    function formaterCatalogue($catalogue, $request): array
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $data = [
            'type' => 'ressource',
            'produits' => []
        ];
        foreach ($catalogue as $produit){
            $data['produits'][] = [
                'id' => $produit->numero_produit,
                'libelle' => $produit->libelle_produit,
                'categorie' => $produit->categorie_id,
                'links' => [
                    'self' => ['href'=> $routeParser->urlFor('produit', ['id_produit' => $produit->numero_produit])],
                    'categorie' => ['href'=> $routeParser->urlFor('produits_par_categorie', ['id_categorie' => $produit->categorie_id])]
                ]
            ];
        }
        return $data;
    }
}