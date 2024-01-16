<?php

namespace pizzashop\catalogue\app\actions;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

abstract class AbstractAction
{
    public abstract function __invoke (Request $request,Response $response, $args): ResponseInterface;

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
                'categorie' => [
                    'id' => $produit->categorie->id,
                    'libelle' => $produit->categorie->libelle
                ],
                'links' => [
                    'self' => ['href'=> $routeParser->urlFor('produit', ['id_produit' => $produit->numero_produit])],
                    'categorie' => ['href'=> $routeParser->urlFor('produits_par_categorie', ['id_categorie' => $produit->categorie->id])]
                ]
            ];
            foreach ($produit->tarifs as $tarif){
                $data['produits']['tarifs'][] = [
                    'taille' => [
                        'id' => $tarif->taille->id,
                        'libelle' => $tarif->taille->libelle
                    ],
                    'tarif' => $tarif->tarif
                ];
            }
        }
        return $data;
    }
}