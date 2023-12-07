<?php

namespace pizzashop\auth\api\domain\services\auth;



use pizzashop\auth\api\domain\dto\CredentialsDTO;
use pizzashop\auth\api\domain\dto\TokenDTO;
use pizzashop\auth\api\domain\dto\UserDTO;
use pizzashop\auth\api\domain\services\exceptions\AccessTokenCreationFailedException;
use pizzashop\auth\api\domain\services\exceptions\AccessTokenExpiredException;
use pizzashop\auth\api\domain\services\exceptions\AccessTokenValidationFailedException;
use pizzashop\auth\api\domain\services\exceptions\CredentialsControlFailedException;
use pizzashop\auth\api\domain\services\exceptions\InactivatedUserException;
use pizzashop\auth\api\domain\services\exceptions\InvalidJwtExpirationException;
use pizzashop\auth\api\domain\services\exceptions\JwtManagerExpiredTokenException;
use pizzashop\auth\api\domain\services\exceptions\JwtManagerInvalidTokenException;
use pizzashop\auth\api\domain\services\exceptions\JwtSecretException;
use pizzashop\auth\api\domain\services\exceptions\PasswordNotMatchException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenControlFailedException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenCreationFailedException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenErrorException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenExpiredException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenNotFoundException;
use pizzashop\auth\api\domain\services\exceptions\UserNotFoundException;

class AuthService implements AuthServiceInterface
{

    private AuthentificationProvider $authProvider;
    private JwtManager $jwtManager;

    public function __construct(AuthentificationProvider $authProvider, JwtManager $jwtManager)
    {
        $this->authProvider = $authProvider;
        $this->jwtManager = $jwtManager;
    }

    /**
     * @param CredentialsDTO $credentialsDTO
     * @return UserDTO
     */
    public function signup(CredentialsDTO $credentialsDTO): UserDTO
    {
        $this->authProvider->register($credentialsDTO->email, $credentialsDTO->password);
        return $this->authProvider->getAuthentifiedUser();
    }

    /**
     * @param CredentialsDTO $credentialsDTO
     * @return TokenDTO
     * @throws CredentialsControlFailedException
     */
    public function signin(CredentialsDTO $credentialsDTO): TokenDTO
    {

        try {
            $this->authProvider->checkCredentials($credentialsDTO->email, $credentialsDTO->password);
        } catch (InactivatedUserException|UserNotFoundException|PasswordNotMatchException|RefreshTokenCreationFailedException $e) {
            throw new CredentialsControlFailedException($e->getMessage());
        }
        $user = $this->authProvider->getAuthentifiedUser();
        $access_token = $this->jwtManager->create($user);
        return new TokenDTO($access_token, $user->refresh_token);
    }

    /**
     * @param TokenDTO $tokenDTO
     * @return UserDTO
     * @throws AccessTokenExpiredException
     * @throws AccessTokenValidationFailedException
     */
    public function validate(TokenDTO $tokenDTO): UserDTO
    {
        try {
            $payload = $this->jwtManager->validate($tokenDTO->access_token);
        } catch (JwtManagerExpiredTokenException $e) {
            throw new AccessTokenExpiredException();
        } catch (JwtManagerInvalidTokenException $e) {
            throw new AccessTokenValidationFailedException();
        }
        return new UserDTO($payload['upr']['username'], $payload['upr']['email']);
    }


    /**
     * @param TokenDTO $tokenDTO
     * @return TokenDTO
     * @throws RefreshTokenControlFailedException
     */
    public function refresh(TokenDTO $tokenDTO): TokenDTO
    {
        try {
            $this->authProvider->checkToken($tokenDTO->refresh_token);
        } catch (RefreshTokenCreationFailedException|RefreshTokenErrorException|RefreshTokenExpiredException|RefreshTokenNotFoundException $e) {
            throw new RefreshTokenControlFailedException();
        }
        $user = $this->authProvider->getAuthentifiedUser();
        $access_token = $this->jwtManager->create($user);
        return new TokenDTO($access_token, $tokenDTO->refresh_token);
    }

    public function activate_signup(TokenDTO $tokenDTO): void
    {
        // TODO: Implement activate_signup() method.
    }

    public function reset_password(TokenDTO $tokenDTO, CredentialsDTO $credentialsDTO): void
    {
        // TODO: Implement reset_password() method.
    }
}