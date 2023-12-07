<?php

namespace pizzashop\auth\api\app\actions;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use pizzashop\auth\api\app\actions\AbstractAction;
use pizzashop\auth\api\domain\dto\TokenDTO;
use pizzashop\auth\api\domain\services\auth\AuthService;
use pizzashop\auth\api\domain\services\exceptions\AccessTokenExpiredException;
use pizzashop\auth\api\domain\services\exceptions\AccessTokenValidationFailedException;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ValidateAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $h = $request->getHeader('Authorization')[0];
        $tokenstring = sscanf($h, "Bearer %s")[0];
        try {
            $userDTO = $this->authService->validate(new TokenDTO($tokenstring));
        } catch (AccessTokenExpiredException|AccessTokenValidationFailedException $e) {
            return $this->showError($e, $response);
        }
        $json = [
            'type' => 'object',
            'user' => [
                'type' => 'object',
                'properties' => [
                    'username' => [
                        'type' => 'string',
                        'value' => $userDTO->username
                    ],
                    'email' => [
                        'type' => 'string',
                        'value' => $userDTO->email
                    ],
                ]
            ]
        ];
        $response->getBody()->write(json_encode($json));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}