<?php

namespace pizzashop\shop\app\actions;

use pizzashop\shop\domain\services\catalogue\ServiceCatalogue;
use pizzashop\shop\domain\services\commande\ServiceCommande;
use pizzashop\shop\domain\services\exceptions\ServiceCommandeInvalidException;
use pizzashop\shop\domain\services\exceptions\ServiceCommandeNotFoundException;
use pizzashop\shop\domain\services\exceptions\ServiceCommandeEnregistrementException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ValiderCommandeAction extends AbstractAction
{
    private ServiceCommande $comm;

    /**
     * @param ServiceCommande $comm
     */
    public function __construct(ServiceCommande $comm)
    {
        $this->comm = $comm;
    }

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

        $response->getBody()->write(json_encode($data));
        return
            $response->withHeader('Content-Type','application/json')
                ->withHeader('Access-Control-Allow-Origin','*')
                ->withStatus(200);
    }
}