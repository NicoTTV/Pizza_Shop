<?php

namespace pizzashop\auth\api\tests;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use pizzashop\auth\api\domain\entities\User;
use pizzashop\auth\api\domain\services\auth\AuthentificationProvider;
use pizzashop\auth\api\domain\services\auth\JwtManager;
use pizzashop\auth\api\domain\services\utils\DB;

class JwtManagerTests extends TestCase
{
    private static $user = [];
    private static JwtManager $jwtManager;
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$jwtManager = new JwtManager();
        $dbcom = __DIR__ . '/../config/auth.db.test.ini';
        $db = new DB();
        $db->addConnection($dbcom, 'auth');


        self::$faker = Factory::create('fr_FR');
        print_r(self::$user);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
    }
}