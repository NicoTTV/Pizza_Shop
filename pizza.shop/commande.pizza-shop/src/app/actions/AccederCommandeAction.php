<?php

namespace pizzashop\commande\app\actions;

use pizzashop\commande\domain\services\commande\ServiceCommande;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe pour l'action d'accéder à une commande spécifique.
 *
 * Cette action gère la récupération des détails d'une commande
 * par son identifiant unique (UUID).
 */
class AccederCommandeAction extends AbstractAction {

    /**
     * Service pour gérer les commandes.
     * @var ServiceCommande
     */
    private ServiceCommande $comm;

    /**
     * Constructeur pour l'action d'accéder à une commande.
     *
     * @param ServiceCommande $comm Service pour les opérations de commande.
     */
    public function __construct(ServiceCommande $comm)
    {
        $this->comm = $comm;
    }

    /**
     * Gère la requête HTTP pour accéder aux détails d'une commande.
     *
     * Récupère l'identifiant de la commande à partir de la requête,
     * interroge le service de commande pour obtenir les détails,
     * et renvoie une réponse avec les données de la commande.
     *
     * @param Request $request La requête HTTP.
     * @param Response $response La réponse HTTP.
     * @param array $args Les arguments de la route.
     * @return ResponseInterface La réponse HTTP avec les détails de la commande.
     * @throws HttpBadRequestException Si l'identifiant de la commande est manquant.
     * @throws HttpNotFoundException Si la commande n'est pas trouvée.
     */
    public function __invoke(Request $request, Response $response, $args): ResponseInterface {

        $UUID = $request->getAttribute('id_commande');
        if (is_null($UUID)) throw new HttpBadRequestException($request, 'Missing id_commande');
        try {
            $commande = $this->comm->accederCommande($UUID);
        } catch (serviceCommandeNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage());
        }

        $data = $this->formaterCommande($commande, $request);


        $response->getBody()->write(json_encode($data));
        return
            $response->withHeader('Content-Type','application/json')
                ->withHeader('Access-Control-Allow-Origin','*')
                ->withStatus(200);

    }

}