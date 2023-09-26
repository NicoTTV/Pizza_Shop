<?php

namespace pizzashop\shop\app\actions;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class AbstractAction
{
    public abstract function __invoke (Request $request,Response $response, $args): ResponseInterface;
}