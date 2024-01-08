<?php

namespace pizzashop\shop\app\actions;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use GuzzleHttp\Client;

class ConnexionAction extends AbstractAction
{

    /**
     * @throws GuzzleException
     */
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        // Extraction des données de la requête client
        $credentials = $request->getParsedBody();

        // Envoi de la requête à l'API d'Authentification
        $client = new Client();
        $res = $client->post('localhost:2780/api/users/signin', [
            'json' => $credentials
        ]);

        // Retour de la réponse de l'API d'Authentification au client
        return $response->withBody($res->getBody())
            ->withStatus($res->getStatusCode())
            ->withHeader('Content-Type', 'application/json');

    }
}