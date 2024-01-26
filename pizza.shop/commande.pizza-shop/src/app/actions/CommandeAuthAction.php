<?php

namespace pizzashop\commande\app\actions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use pizzashop\commande\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CommandeAuthAction extends AbstractAction
{
    private Client $client;
    public function __construct($authUrl)
    {
        $this->client = new Client([
            'base_uri' => "http://$authUrl/"
        ]);
    }

    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        try {
            $response = $this->client->request($request->getMethod(), '/user/signin', [
                'headers' => $request->getHeaders(),
                'json' => $request->getParsedBody()
            ]);
        }catch (ConnectException | ServerException $e) {
            throw new HttpInternalServerErrorException($request, "auth server error ({$e->getCode()},{$e->getMessage()})");
        }catch (ClientException $e) {
            if ($e->getCode() === 401)
                throw new HttpUnauthorizedException($request, "auth error ({$e->getCode()},{$e->getMessage()})");
            throw new HttpInternalServerErrorException($request, "client error ({$e->getCode()},{$e->getMessage()})");
        }
        return $response;
    }
}