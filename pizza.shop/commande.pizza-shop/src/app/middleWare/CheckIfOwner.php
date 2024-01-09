<?php

namespace pizzashop\commande\app\middleWare;

use pizzashop\commande\domain\services\commande\ServiceCommande;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeInvalidException;
use pizzashop\commande\domain\services\exceptions\ServiceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

class CheckIfOwner
{
    private ServiceCommande $serviceCommande;
    public function __construct(ServiceCommande $serviceCommande)
    {
        $this->serviceCommande = $serviceCommande;
    }
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        $email = $request->getAttribute('user')->user->properties->email->value;
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