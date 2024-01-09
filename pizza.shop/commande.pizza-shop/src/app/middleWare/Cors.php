<?php

namespace pizzashop\commande\app\middleWare;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

class Cors {
    public function __invoke(Request $request, RequestHandlerInterface $handler): Response {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');
        $origin = $request->getHeader('Origin');

        $response = $handler->handle($request);

        // Ajouter l'en-tête 'Access-Control-Allow-Origin' seulement si '$origin' n'est pas vide
        if (!empty($origin)) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $origin[0]);
        }
        $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
        $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

        return $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    }
}