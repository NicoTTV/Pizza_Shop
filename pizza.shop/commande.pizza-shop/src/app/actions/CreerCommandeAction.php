<?php

namespace pizzashop\commande\app\actions;

use pizzashop\commande\domain\dto\commandeDTO;
use pizzashop\commande\domain\dto\ItemDTO;
use pizzashop\commande\domain\services\commande\ServiceCommande;
use pizzashop\commande\domain\services\exceptions\CreerCommandeException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Action pour créer une nouvelle commande.
 *
 * Cette action gère la création d'une commande à partir des données reçues
 * dans la requête HTTP.
 */
class CreerCommandeAction extends AbstractAction {

    /**
     * Service pour la gestion des commandes.
     *
     * @var ServiceCommande
     */
    private ServiceCommande $comm;

    /**
     * Constructeur pour l'action de création de commande.
     *
     * @param ServiceCommande $comm Le service de gestion des commandes.
     */
    public function __construct(ServiceCommande $comm)
    {
        $this->comm = $comm;
    }

    /**
     * Traite la requête HTTP pour créer une nouvelle commande.
     *
     * Lit les données de la requête, crée un DTO de commande,
     * puis appelle le service de commande pour créer la commande.
     * Enfin, renvoie une réponse HTTP avec les détails de la commande créée.
     *
     * @param Request $request La requête HTTP.
     * @param Response $response La réponse HTTP.
     * @param array $args Les arguments de la route.
     * @return ResponseInterface La réponse HTTP avec les détails de la commande créée.
     * @throws HttpBadRequestException Si les données de la commande sont manquantes ou invalides.
     */
    public function __invoke (Request $request,Response $response, $args): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $commandeDTO = new CommandeDTO($data['mail_client'], $data['type_livraison']);
        foreach ($data['items'] as $item) {
            $commandeDTO->addItem(new ItemDTO($item['numero'], $item['taille'], $item['quantite']));
        }
        try {
            $commande = $this->comm->creerCommande($commandeDTO);
        } catch (CreerCommandeException $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }

        $api = $this->formaterCommande($commande, $request);
        $response->getBody()->write(json_encode($api));
        return
            $response->withHeader('Content-Type', 'application/json')
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withStatus(200);
    }

}