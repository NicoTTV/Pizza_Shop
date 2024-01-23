<?php

namespace pizzashop\auth\api\domain\services\auth;

use DateTime;
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
use pizzashop\auth\api\domain\services\exceptions\UserAlreadyExistsException;
use pizzashop\auth\api\domain\services\exceptions\UserNotFoundException;
use Throwable;

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
            throw new UserNotFoundException("user not found");
        }

        if (isset($user)) {
            if ($user->active === 0) {
                throw new InactivatedUserException("user not activated");
            }
            if (!password_verify($password, $user->password)) {
                throw new PasswordNotMatchException("password not match");
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
        try {
            $user = User::where('refresh_token', $token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new RefreshTokenNotFoundException("user not found");
        }
        try {
            if (new DateTime($user->refresh_token_expiration_date) < date('Y-m-d H:i:s'))
                throw new RefreshTokenExpiredException("refresh token expired");
        } catch (\Exception $e) {
            throw new RefreshTokenErrorException("Une erreur est survenue ".$e->getMessage());
        }
        $this->createRefreshToken($user);
        $this->user = $user;
    }

    public function getAuthentifiedUser(): UserDTO
    {
        return new UserDTO($this->user->username, $this->user->email, $this->user->refresh_token);
    }

    /**
     * @throws UserAlreadyExistsException
     * @throws \Exception
     */
    public function register(string $email, string $pass, string $username): void
    {
        if (User::where('email', $email)->exists())
            throw new UserAlreadyExistsException();

        $user = array(  'email' => $email,
                        'password' => password_hash($pass, PASSWORD_DEFAULT),
                        'activation_token' => bin2hex(random_bytes(32)),
                        'username' => $username,
        );
        $user = User::create($user);
        $this->user = $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function activate(string $token): void
    {
        try {
            $user = User::where('activation_token', $token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }
        $user->active = 1;
        $user->activation_token = null;
        $user->saveOrFail();
    }

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
        } catch (Throwable $e) {
            throw new RefreshTokenCreationFailedException($e->getMessage());
        }
    }

    public function unsetRefreshTokenForTest(): void
    {
        $this->refreshToken = "test";
    }
}