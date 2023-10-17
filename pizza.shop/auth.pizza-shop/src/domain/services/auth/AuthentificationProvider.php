<?php

namespace pizzashop\auth\api\domain\services\auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Exception;
use pizzashop\auth\api\domain\dto\UserDTO;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\services\exceptions\InactivatedUserException;
use pizzashop\auth\api\domain\services\exceptions\PasswordNotMatchException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenCreationFailedException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenErrorException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenExpiredException;
use pizzashop\auth\api\domain\services\exceptions\RefreshTokenNotFoundException;
use pizzashop\auth\api\domain\services\exceptions\UserNotFoundException;

class AuthentificationProvider
{

    private string $refreshToken;
    private User $user;

    /**
     * @param $email
     * @param $password
     * @throws InactivatedUserException
     * @throws PasswordNotMatchException
     * @throws RefreshTokenCreationFailedException
     * @throws UserNotFoundException
     */
    public function checkCredentials($email, $password): void
    {
        try {
            $user = User::findOrFail($email);
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
        $this->createRefreshToken($user);
        $this->user = $user;
    }

    /**
     * @param $token
     * @throws RefreshTokenCreationFailedException
     * @throws RefreshTokenErrorException
     * @throws RefreshTokenExpiredException
     * @throws RefreshTokenNotFoundException
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
        $this->createRefreshToken($user);
        $this->user = $user;
    }

    public function getAuthentifiedUser(): UserDTO {
        return new UserDTO($this->user->username, $this->user->email, $this->user->refresh_token);
    }
    
    public function register(string $user, string $pass) {}

    public function activate(string $token) {}

    /**
     * @throws RefreshTokenCreationFailedException
     */
    public function createRefreshToken($user): void
    {
        try {
            $refreshToken = bin2hex(random_bytes(32));
            $user->refresh_token = $refreshToken;
            $user->refresh_token_expiration_date = date('Y-m-d H:i:s', strtotime('+1 year'));
            $user->saveOrFail();
        }catch (\Throwable $e) {
            throw new RefreshTokenCreationFailedException($e->getMessage());
        }
    }

    public function unsetRefreshTokenForTest(): void
    {
        $this->refreshToken = "test";
    }
}