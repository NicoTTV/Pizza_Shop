<?php

namespace pizzashop\gateway\app\actions;

use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

abstract class AbstractGatewayAction
{
    protected Client $service;
    public function __construct(string $authUrl)
    {
        $this->service = new Client([
            'base_uri' => "http://$authUrl/"
        ]);
    }

    public abstract function __invoke (Request $request,Response $response, $args): ResponseInterface;
}