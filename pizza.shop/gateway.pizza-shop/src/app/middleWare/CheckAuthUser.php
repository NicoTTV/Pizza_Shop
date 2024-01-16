<?php

namespace pizzashop\gateway\app\middleWare;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

class CheckAuthUser
{
    private Client $client;
    public function __construct(string $authUrl)
    {
        $this->client = new Client([
            'base_uri' => $authUrl,
            'timeout' => 5.0,
        ]);
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        try {
            $response = $this->client->request($request->getMethod(),'validate', [
                'headers' => $request->getHeaders(),
            ]);
        }catch (ClientException | ServerException | ConnectException $e) {
            throw new HttpUnauthorizedException($request, "auth error ({$e->getCode()},{$e->getMessage()})");
        }
        if (!$response->getStatusCode() == 200)
            throw new HttpUnauthorizedException($request, 'Invalid token');

        return $next->handle($request);
    }
}