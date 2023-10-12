<?php

namespace pizzashop\auth\api\tests;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\services\auth\AuthentificationProvider;
use pizzashop\auth\api\domain\services\utils\DB;

require_once __DIR__ . '/../vendor/autoload.php';

class AuthProviderTest extends TestCase
{
    private static $user = [];
    private static AuthentificationProvider $authProvider;
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$authProvider = new AuthentificationProvider();
        $dbcom = __DIR__ . '/../config/auth.db.test.ini';
        $db = new DB();
        $db->addConnection($dbcom, 'auth');


        self::$faker = Factory::create('fr_FR');
        self::fillDB();
        print_r(self::$user);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::cleanDB();
    }


    private static function cleanDB()
    {
        foreach (self::$user as $user) {
            $currentUser = User::find($user["email"]);
            $currentUser->delete();
        }
    }

    private static function fillDB()
    {
        for ($i = 0; $i < 7; $i++) {
            $password = self::$faker->password;
            $email = self::$faker->email;
            $user = new User();
            $user->email = $email;
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            if ($i === 5) {
                $user->refresh_token_expiration_date = self::$faker->dateTimeBetween('-1 year', 'now');
                $user->active = 0;
            }
        else {
                $user->refresh_token_expiration_date = self::$faker->dateTimeBetween('now', '+1 year');
                $user->active = 1;
            }
            $user->refresh_token = self::$faker->uuid;
            $user->username = self::$faker->userName;
            $user->save();
            self::$user[] = [
                "email" => $email,
                "password" => $password,
                "refresh_token" => $user->refresh_token,
                "refresh_token_expiration_date" => $user->refresh_token_expiration_date,
                "username" => $user->username,
            ];
        }
    }


    public function testCheckCredentialsWithUnvalidCredentials()
    {
        $this->expectException(\pizzashop\auth\api\domain\services\exceptions\UserNotFoundException::class);
        self::$authProvider->checkCredentials(self::$faker->uuid, self::$faker->password);
    }

    public function testCheckCredentialsWithInactivatedUser()
    {
        $this->expectException(\pizzashop\auth\api\domain\services\exceptions\InactivatedUserException::class);
        $user = self::$user[5];
        self::$authProvider->checkCredentials($user["email"], $user["password"]);
    }

    public function testCheckCredentialsWithUnvalidPassword()
    {
        $this->expectException(\pizzashop\auth\api\domain\services\exceptions\PasswordNotMatchException::class);
        $user = self::$user[6];
        self::$authProvider->checkCredentials($user["email"], self::$faker->password);
    }

    public function testCheckCredentialsWithValidCredentials()
    {
        $user = self::$user[0];
        self::$authProvider->checkCredentials($user["email"], $user["password"]);
        $this->assertTrue(true);
    }

    public function testCheckTokenWithUnvalidToken()
    {
        $this->expectException(\pizzashop\auth\api\domain\services\exceptions\RefreshTokenNotFoundException::class);
        self::$authProvider->checkToken(self::$faker->uuid);
    }

    public function testCheckTokenWithExpiredToken()
    {
        $this->expectException(\pizzashop\auth\api\domain\services\exceptions\RefreshTokenExpiredException::class);
        $user = self::$user[5];
        self::$authProvider->checkToken($user["refresh_token"]);
    }

    public function testCheckTokenWithValidToken()
    {
        $user = self::$user[0];
        self::$authProvider->checkToken($user["refresh_token"]);
        $this->assertTrue(true);
    }

    public function testGetAuthentifiedUser()
    {
        $user = self::$user[0];
        self::$authProvider->checkToken($user["refresh_token"]);
        $userDTO = self::$authProvider->getAuthentifiedUser();
        $this->assertEquals($user["email"], $userDTO->email);
        $this->assertEquals($user["username"], $userDTO->username);
        $this->assertEquals($user["refresh_token"], $userDTO->refresh_token);
    }

    public function testGetAuthentifiedUserWithUnvalidTokenOrUnidentified()
    {
        $this->expectException(\pizzashop\auth\api\domain\services\exceptions\UserNotFoundException::class);
        self::$authProvider->unsetRefreshTokenForTest();
        self::$authProvider->getAuthentifiedUser();
    }

    public function testGetAuthentifiedUserWithExpiredToken()
    {
        $this->expectException(\pizzashop\auth\api\domain\services\exceptions\RefreshTokenExpiredException::class);
        $user = self::$user[5];
        self::$authProvider->checkToken($user["refresh_token"]);
        self::$authProvider->getAuthentifiedUser();
    }

}