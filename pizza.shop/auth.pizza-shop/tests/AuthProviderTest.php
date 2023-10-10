<?php

namespace pizzashop\auth\api\tests;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\services\AuthentificationProvider;
use pizzashop\auth\api\domain\services\utils\DB;

require_once __DIR__ . '/../vendor/autoload.php';

class AuthProviderTest extends TestCase
{
    private static $userId = [];
    private static AuthentificationProvider $authProvider;
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $dbcom = __DIR__ . '/../config/auth.db.test.ini';
        $db = new DB();
        $db->addConnection($dbcom, 'auth');


        self::$faker = Factory::create('fr_FR');
        self::fillDB();
        print_r(self::$userId);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDown();
        self::cleanDB();
    }


    private static function cleanDB()
    {
        foreach (self::$userId as $id) {
            User::find($id)->delete();
        }
    }

    private static function fillDB()
    {
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->email = self::$faker->email;
            $user->password = self::$faker->password;
            $user->active = self::$faker->boolean;
            $user->refresh_token = self::$faker->uuid;
            $user->refresh_token_expiration_date = self::$faker->dateTimeBetween('-1 year', 'now');
            $user->username = self::$faker->userName;
            $user->save();
            self::$userId[] = $user->email;
        }
    }


    public function testCheckCredentialsWithUnvalidCredentials()
    {
        $this->expectException(\pizzashop\auth\api\domain\services\exceptions\UserNotFoundException::class);
        self::$authProvider->checkCredentials(self::$faker->uuid, self::$faker->password);
    }

}