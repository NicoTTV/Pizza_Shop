<?php

namespace pizzashop\commande\app\middleWare;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

/**
 * Middleware pour vérifier l'authentification de l'utilisateur.
 *
 * Ce middleware intercepte la requête et vérifie la présence et la validité
 * du token d'authentification fourni dans l'en-tête 'Authorization'.
 */
class CheckAuthUser
{
    /**
     * Méthode invoquée lors du traitement de la requête par le middleware.
     *
     * Vérifie l'existence et la validité du token d'authentification.
     * Si le token est absent ou invalide, une exception HttpUnauthorizedException est levée.
     * Si le token est valide, la requête est enrichie avec les données de l'utilisateur
     * et passée au prochain gestionnaire.
     *
     * @param ServerRequestInterface $request La requête HTTP.
     * @param RequestHandlerInterface $next Le prochain gestionnaire de requête.
     * @return ResponseInterface La réponse HTTP après traitement du middleware.
     * @throws HttpUnauthorizedException|GuzzleException Si aucun token n'est fourni ou si le token est invalide.
     */
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {

        $h = $request->getHeader('Authorization');
        if (empty($h))
            throw new HttpUnauthorizedException($request, 'No token provided');
        $tokenstring = sscanf($h[0], "Bearer %s")[0];

        $authClient = new Client([
            'base_uri' => 'auth-api.pizza-shop/api/users/',
            'timeout' => 10.0
        ]);
        $authorization = [  'Authorization' => "Bearer $tokenstring",
                            'Content-Type' => 'application/json'];
        $response = $authClient->get('validate', [
            'headers' => $authorization
        ]);
        if (!$response->getStatusCode() == 200)
            throw new HttpUnauthorizedException($request, 'Invalid token');

        $request = $request->withAttribute('user', json_decode($response->getBody()));
        return $next->handle($request);

    }
}