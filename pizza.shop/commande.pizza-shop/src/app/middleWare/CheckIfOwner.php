<?php

namespace pizzashop\commande\app\middleWare;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use pizzashop\commande\domain\services\commande\ServiceCommande;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeInvalidException;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

class CheckIfOwner
{
    private ServiceCommande $serviceCommande;
    private Client $authClient;
    public function __construct(ServiceCommande $serviceCommande, string $authUrl)
    {
        $this->serviceCommande = $serviceCommande;
        $this->authClient = new Client([
            'base_uri' => "auth-api.pizza-shop/user/"
        ]);
    }
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        try {
            $response = $this->authClient->get('validate', [
                'headers' => $request->getHeaders()
            ]);
        }catch (ConnectException | ServerException $e) {
            var_dump($e->getMessage());
            throw new HttpInternalServerErrorException($request, "auth server error ({$e->getCode()},{$e->getMessage()})");
        }catch (ClientException $e) {
            if ($e->getCode() === 401)
                throw new HttpUnauthorizedException($request, "auth error ({$e->getCode()},{$e->getMessage()})");
            throw new HttpInternalServerErrorException($request, "client error ({$e->getCode()},{$e->getMessage()})");
        }
        $email = json_decode($response->getBody()->getContents())->user->properties->email->value;

        if (!$response->getStatusCode() == 200)
            throw new HttpUnauthorizedException($request, 'Invalid token');

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $idCommande = $route->getArgument('id_commande');
        try {
            $this->serviceCommande->checkIfUserIsOwner($idCommande, $email);
        } catch (ServiceCommandeInvalidException|ServiceCommandeNotFoundException $e) {
            throw new HttpUnauthorizedException($request, $e->getMessage());
        }
        return $next->handle($request);
    }
}