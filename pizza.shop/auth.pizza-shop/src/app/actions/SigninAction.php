<?php

namespace pizzashop\auth\api\app\actions;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use pizzashop\auth\api\domain\dto\CredentialsDTO;
use pizzashop\auth\api\domain\services\auth\AuthService;
use pizzashop\auth\api\domain\services\exceptions\AccessTokenCreationFailedException;
use pizzashop\auth\api\domain\services\exceptions\CredentialsControlFailedException;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class SigninAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $h = $request->getHeader('Authorization')[0];
        $tokenstring = sscanf($h, "Basic %s")[0];
        $token = explode(':',base64_decode($tokenstring));
        $credentialsDTO = new CredentialsDTO($token[0], $token[1]);
        try {
            $tokenDTO = $this->authService->signin($credentialsDTO);
        } catch (CredentialsControlFailedException $e) {
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