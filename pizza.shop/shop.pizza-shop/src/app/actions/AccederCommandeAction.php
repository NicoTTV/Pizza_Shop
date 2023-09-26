<?php

namespace pizzashop\shop\app\actions;

use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\services\catalogue\ServiceCatalogue;
use pizzashop\shop\domain\services\commande\ServiceCommande;
use pizzashop\shop\domain\services\commande\serviceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AccederCommandeAction extends AbstractAction {

    public function __invoke(Request $request,Response $response, $args): ResponseInterface {
        $cat = new ServiceCatalogue();
        $comm = new ServiceCommande($cat);
        $UUID = $request->getAttribute('id_commande');
        try {
            $commande = $comm->accederCommande($UUID);
        } catch (serviceCommandeNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage());
        }

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


        $response->getBody()->write(json_encode($data));
        return
            $response->withHeader('Content-Type','application/json')
                ->withHeader('Access-Control-Allow-Origin','*')
                ->withStatus(200);

    }

}