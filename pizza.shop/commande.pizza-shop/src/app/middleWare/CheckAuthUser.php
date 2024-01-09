<?php

namespace pizzashop\commande\app\middleWare;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

class CheckAuthUser
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {

        $h = $request->getHeader('Authorization');
        if (empty($h))
            throw new HttpUnauthorizedException($request, 'No token provided');
        $tokenstring = sscanf($h[0], "Bearer %s")[0];

        $authClient = new Client([
            'base_uri' => 'api.pizza-auth/api/users/',
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