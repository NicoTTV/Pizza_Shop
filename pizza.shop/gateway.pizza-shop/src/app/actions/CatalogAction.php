<?php

namespace pizzashop\gateway\app\actions;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use pizzashop\gateway\app\actions\AbstractGatewayAction;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CatalogAction extends AbstractGatewayAction
{

    /**
     * @throws GuzzleException
     */
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        try {
            return $this->service->request($request->getMethod(), $request->getUri()->getPath(), [
                'query' => $request->getQueryParams(),
            ]);
        }catch(ConnectException | ServerException | ClientException $e) {
            throw new HttpInternalServerErrorException($request, "auth server error ({$e->getCode()},{$e->getMessage()})");
        }
    }
}