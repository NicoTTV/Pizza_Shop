<?php

namespace pizzashop\auth\api\domain\services\auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use pizzashop\auth\api\domain\services\exceptions\InvalidJwtExpirationException;
use pizzashop\auth\api\domain\services\exceptions\JwtSecretException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenExpiredException;
use pizzashop\auth\api\domain\services\exceptions\UserNotFoundException;
use stdClass;

class JwtManager
{

    /**
     * @throws InvalidJwtExpirationException
     * @throws JwtSecretException
     */
    public function create(array $payload): string
    {
        try {
            $expiration = intval(getenv('JWT_EXPIRES_IN_S'));
        }catch (\Exception $e) {
            throw new InvalidJwtExpirationException();
        }
        try {
            $secret = getenv('JWT_SECRET');
        }catch (\Exception $e) {
            throw new JwtSecretException();
        }

        $payload[] = [
            'iat' => time(),
            'exp' => time() + $expiration,
        ];

        return JWT::encode($payload, $secret, 'HS512');
    }

    /**
     * @throws RefreshTokenExpiredException
     */
    public function validate(string $token): stdClass
    {
        $secret = getenv('JWT_SECRET');

        $payload = JWT::decode($token, new Key($secret, 'HS512'));
        if ($payload->exp < time()) {
            throw new RefreshTokenExpiredException();
        }

        return $payload;
    }
}