<?php

namespace pizzashop\auth\api\app\actions;

use pizzashop\auth\api\domain\dto\CredentialsDTO;
use pizzashop\auth\api\domain\services\auth\AuthService;
use pizzashop\auth\api\domain\services\exceptions\UserAlreadyExistsException;
use pizzashop\auth\api\domain\services\exceptions\UserNotRegisteredException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class SignupAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $body = $request->getParsedBody();
        $email = $body['email'];
        $password = $body['password'];
        $username = $body['username'];
        try {
            $user = $this->authService->signup(new CredentialsDTO($email, $password, $username));
        } catch (UserAlreadyExistsException $e) {
            throw new HttpBadRequestException($request, "signup error ({$e->getMessage()})");
        } catch (UserNotRegisteredException $e) {
            throw new HttpInternalServerErrorException($request, "signup error ({$e->getMessage()})");
        }
        $json = [
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'value' => $user->email
                ],
                'username' => [
                    'type' => 'string',
                    'value' => $user->username
                ],
            ]
        ];
        $response->getBody()->write(json_encode($json));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}