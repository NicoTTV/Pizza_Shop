<?php

namespace pizzashop\auth\api\app\actions;

use pizzashop\auth\api\domain\services\auth\AuthService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class AbstractAction
{

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public abstract function __invoke (Request $request,Response $response, $args): ResponseInterface;

    protected function showError($error, $response) {
        $response->getBody()->write(json_encode(['error' => $error->getMessage()]));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}