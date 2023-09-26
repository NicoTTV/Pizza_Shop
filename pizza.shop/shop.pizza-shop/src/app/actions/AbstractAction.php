<?php

namespace pizzashop\shop\app\actions;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class AbstractAction
{
    public abstract function __invoke(Response $response, Request $request, $args): ResponseInterface;
}