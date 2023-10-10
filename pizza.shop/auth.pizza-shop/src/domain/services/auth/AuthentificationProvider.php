<?php

namespace pizzashop\auth\api\domain\services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Exception;
use pizzashop\auth\api\domain\dto\UserDTO;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\services\exceptions\InactivatedUserException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenErrorException;
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
     */
    public function checkToken($token): void
    {
        try {
            if (!User::where('refresh_token', $token)->exists())
                throw new RefreshTokenNotFoundException();
        }catch (Exception $e) {
            throw new RefreshTokenErrorException($e->getMessage());
        }
    }
    
    public function getAuthentifiedUser(): UserDTO {
        $user = User::select('username','email','refresh_token')->where('refresh_token', $this->refreshToken)->firstOrFail();
        return new UserDTO($user->username, $user->email, $user->refresh_token);
    }
    
    public function register(string $user, string $pass) {}

    public function activate(string $token) {}
}