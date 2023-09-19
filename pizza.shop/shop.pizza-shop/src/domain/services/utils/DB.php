<?php

namespace pizzashop\shop\domain\services\utils;

use Illuminate\Database\Capsule\Manager;

class DB extends Manager
{
    public static function initConnection(): void
    {
        $db = new Manager();
        $db->addConnection(parse_ini_file(__DIR__ . '/../../../../config/catalog.db.ini'));
        $db->addConnection(parse_ini_file(__DIR__ . '/../../../../config/commande.db.ini'));
        $db->setAsGlobal();
        $db->bootEloquent();
    }
}