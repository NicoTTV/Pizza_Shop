<?php

namespace pizzashop\gateway\app\actions;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ShopAction extends AbstractGatewayAction
{

    /**
     * @throws GuzzleException
     */
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        try {
            $response = $this->service->request($request->getMethod(), $request->getUri()->getPath(), [
                'headers' => [
                    'Authorization' => $request->getHeader('Authorization')
                ],
                'json' => $request->getParsedBody()
            ]);
        } catch (ConnectException | ServerException $e) {
            throw new HttpInternalServerErrorException($request, "auth server error ({$e->getCode()},{$e->getMessage()})");
        }catch (ClientException $e) {
            if ($e->getCode() === 401)
                throw new HttpUnauthorizedException($request, "auth error ({$e->getCode()},{$e->getMessage()})");
            throw new HttpInternalServerErrorException($request, "client error ({$e->getCode()},{$e->getMessage()})");
        }
        return $response;
    }
}