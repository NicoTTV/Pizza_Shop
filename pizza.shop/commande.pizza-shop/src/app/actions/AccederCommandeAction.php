<?php

namespace pizzashop\commande\app\actions;

use pizzashop\commande\domain\services\commande\ServiceCommande;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AccederCommandeAction extends AbstractAction {

    private ServiceCommande $comm;

    /**
     * @param ServiceCommande $comm
     */
    public function __construct(ServiceCommande $comm)
    {
        $this->comm = $comm;
    }


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