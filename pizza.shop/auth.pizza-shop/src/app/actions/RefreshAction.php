<?php

namespace pizzashop\auth\api\app\actions;

use pizzashop\auth\api\app\actions\AbstractAction;
use pizzashop\auth\api\domain\dto\TokenDTO;
use pizzashop\auth\api\domain\services\exceptions\AccessTokenValidationFailedException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenControlFailedException;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class RefreshAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $h = $request->getHeader('Authorization')[0];
        $tokenstring = sscanf($h, "Bearer %s")[0];
        try {
            $tokenDTO = $this->authService->refresh(new TokenDTO(refresh_token: $tokenstring));
        } catch (RefreshTokenControlFailedException $e) {
            return $this->showError($e, $response);
        }
        $json = [
            'type' => 'object',
            'properties' => [
                'access_token' => [
                    'type' => 'string',
                    'value' => $tokenDTO->access_token
                ],
                'refresh_token' => [
                    'type' => 'string',
                    'value' => $tokenDTO->refresh_token
                ],
            ]
        ];
        $response->getBody()->write(json_encode($json));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}