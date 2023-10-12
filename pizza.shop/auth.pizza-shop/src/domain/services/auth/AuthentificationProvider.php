<?php

namespace pizzashop\auth\api\domain\services\auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Exception;
use pizzashop\auth\api\domain\dto\UserDTO;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\services\exceptions\InactivatedUserException;
use pizzashop\auth\api\domain\services\exceptions\PasswordNotMatchException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenErrorException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenExpiredException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenNotFoundException;
use pizzashop\auth\api\domain\services\exceptions\UserNotFoundException;

class AuthentificationProvider
{

    private string $refreshToken;
    /**
     * @throws UserNotFoundException
     * @throws InactivatedUserException
     * @throws PasswordNotMatchException
     */
    public function checkCredentials($userId, $password): void
    {
        try {
            $user = User::select('username','email','password','active')->findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }

        if (isset($user)) {
            if ($user->active === 0) {
                throw new InactivatedUserException();
            }
            if (!password_verify($password, $user->password)) {
                throw new PasswordNotMatchException();
            }
        }

    }

    /**
     * @throws RefreshTokenNotFoundException
     * @throws RefreshTokenErrorException
     * @throws RefreshTokenExpiredException
     */
    public function checkToken($token): void
    {
        $user = User::where('refresh_token', $token);
        try {
            if (!$user->exists())
                throw new RefreshTokenNotFoundException();
            else {
                $user = $user->firstOrFail();
                if ($user->refresh_token_expiration_date < date('Y-m-d H:i:s'))
                    throw new RefreshTokenExpiredException();
            }
        }catch (Exception $e) {
            throw new RefreshTokenErrorException($e->getMessage());
        }

        $this->refreshToken = $token;
    }

    /**
     * @throws UserNotFoundException
     * @throws RefreshTokenExpiredException
     */
    public function getAuthentifiedUser(): UserDTO {
        try {
            $user = User::select('email','username','refresh_token', 'refresh_token_expiration_date')->where('refresh_token', $this->refreshToken)->firstOrFail();
        }catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }
        if ($user->refresh_token_expiration_date < date('Y-m-d H:i:s'))
            throw new RefreshTokenExpiredException();
        return new UserDTO($user->username, $user->email, $user->refresh_token);
    }
    
    public function register(string $user, string $pass) {}

    public function activate(string $token) {}

    public function unsetRefreshTokenForTest(): void
    {
        $this->refreshToken = "test";
    }
}