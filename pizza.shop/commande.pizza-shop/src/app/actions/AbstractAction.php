<?php

namespace pizzashop\commande\app\actions;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

/**
 * Classe de base abstraite pour les actions de l'application de commande de Pizza Shop.
 * Fournit des méthodes communes et une structure pour les actions spécifiques.
 */
abstract class AbstractAction
{
    /**
     * Méthode abstraite qui doit être implémentée par les classes enfant.
     * Cette méthode sera invoquée pour traiter la requête HTTP.
     *
     * @param Request $request L'objet requête PSR-7.
     * @param Response $response L'objet réponse PSR-7.
     * @param array $args Les arguments de la route.
     * @return ResponseInterface L'objet réponse après traitement de la requête.
     */
    public abstract function __invoke (Request $request,Response $response, $args): ResponseInterface;

    /**
     * Formate les données de la commande pour la réponse HTTP.
     * Construit un tableau structuré avec les informations de la commande et les liens associés.
     *
     * @param object $commande L'objet commande contenant les détails de la commande.
     * @param Request $request L'objet requête PSR-7 utilisé pour générer les liens de l'API.
     * @return array Un tableau associatif contenant les données formatées de la commande.
     */
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