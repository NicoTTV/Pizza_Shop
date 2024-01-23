<?php

namespace pizzashop\auth\api\app\actions;

use pizzashop\auth\api\domain\services\auth\AuthService;
use pizzashop\shop\app\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class SignupAction extends AbstractAction
{

    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $h = $request->getHeader('Authorization')[0];
        $tokenstring = sscanf($h, "Basic %s")[0];
        $token = explode(':',base64_decode($tokenstring));

    }
}