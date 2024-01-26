<?php

namespace pizzashop\commande\app\actions;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use pizzashop\commande\domain\services\commande\ServiceCommande;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeInvalidException;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeNotFoundException;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeEnregistrementException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Action pour valider une commande existante.
 *
 * Cette action est responsable de la validation d'une commande
 * basée sur son identifiant unique.
 */
class ValiderCommandeAction extends AbstractAction
{
    /**
     * Service pour la gestion des commandes.
     *
     * @var ServiceCommande
     */
    private ServiceCommande $comm;

    /**
     * Canal AMQP pour la publication des commandes validées.
     *
     * @var AMQPChannel
     */
    private AMQPChannel $amqpChannel;

    /**
     * Constructeur pour l'action de validation de commande.
     *
     * @param ServiceCommande $comm Le service de gestion des commandes.
     * @param AMQPChannel $amqpChannel Le canal AMQP pour la publication des commandes validées.
     */
    public function __construct(ServiceCommande $comm, AMQPChannel $amqpChannel)
    {
        $this->comm = $comm;
        $this->amqpChannel = $amqpChannel;
    }

    /**
     * Gère la requête HTTP pour valider une commande.
     *
     * Récupère l'identifiant de la commande à partir des arguments de la requête,
     * tente de valider la commande via le service de commande,
     * et renvoie une réponse avec les détails de la commande validée.
     *
     * @param Request $request La requête HTTP.
     * @param Response $response La réponse HTTP.
     * @param array $args Les arguments de la route.
     * @return ResponseInterface La réponse HTTP avec les détails de la commande validée.
     * @throws HttpNotFoundException Si la commande n'est pas trouvée.
     * @throws HttpBadRequestException Si la commande est invalide.
     * @throws HttpInternalServerErrorException Si une erreur interne se produit lors de la validation.
     */
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $uuid = $args["id_commande"] ?? "";
        try {
            $commande = $this->comm->validerCommande($uuid);
        } catch (ServiceCommandeNotFoundException $e) {
            throw new HttpNotFoundException($request, "Commande inexistante");
        } catch (ServiceCommandeInvalidException $e) {
            throw new HttpBadRequestException($request, "Commande invalide");
        } catch (ServiceCommandeEnregistrementException $e) {
            throw new HttpInternalServerErrorException($request, "Une erreur est survenue pendant la validation de la commande");
        }

        $data = $this->formaterCommande($commande, $request);

        $msg = new AMQPMessage(json_encode($data));
        $this->amqpChannel->basic_publish($msg, '', getenv('rabbit_queue_commande'));


        $response->getBody()->write(json_encode($data));
        return
            $response->withHeader('Content-Type','application/json')
                ->withHeader('Access-Control-Allow-Origin','*')
                ->withStatus(200);
    }
}