<?php

namespace pizzashop\auth\api\domain\services\auth;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use pizzashop\auth\api\domain\dto\UserDTO;
use pizzashop\auth\api\domain\services\exceptions\InvalidJwtExpirationException;
use pizzashop\auth\api\domain\services\exceptions\JwtManagerExpiredTokenException;
use pizzashop\auth\api\domain\services\exceptions\JwtManagerInvalidTokenException;
use pizzashop\auth\api\domain\services\exceptions\JwtSecretException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenExpiredException;
use pizzashop\auth\api\domain\services\exceptions\UserNotFoundException;
use stdClass;

class JwtManager
{
    private string $issuer;
    private int $expiration;
    private string $secret;

    /**
     * @param int $expiration
     * @param string $secret
     */
    public function __construct(int $expiration, string $secret)
    {
        $this->expiration = $expiration;
        $this->secret = $secret;
    }


    public function setIssuer(string $issuer): void
    {
        $this->issuer = $issuer;
    }

    /**
     * @param UserDTO $user
     * @return string
     */
    public function create(UserDTO $user): string
    {
        $payload[] = [
            'iss' => 'pizza-shop',
            'iat' => time(),
            'exp' => time() + $this->expiration,
            'upr' => [
                'username' => $user->username,
                'email' => $user->email,
            ]
        ];
        return JWT::encode($payload, $this->secret, 'HS512');
    }

    /**
     * @param string $token
     * @return stdClass
     * @throws JwtManagerExpiredTokenException
     * @throws JwtManagerInvalidTokenException
     */
    public function validate(string $token): stdClass
    {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS512'));
        }catch (ExpiredException $e) {
            throw new JwtManagerExpiredTokenException("token expired");
        }catch (SignatureInvalidException | \UnexpectedValueException | \DomainException $e) {
            throw new JwtManagerInvalidTokenException("token invalid");
        }
    }
}