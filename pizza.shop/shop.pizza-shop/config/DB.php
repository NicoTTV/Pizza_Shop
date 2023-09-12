<?php

namespace gift\app\services\utils;

use Illuminate\Database\Capsule\Manager;

class DB extends Manager
{
    public static function initConnection(): void
    {
        $db = new Manager();
        $db->addConnection(parse_ini_file(__DIR__.'catalog.db.ini'));
        $db->addConnection(parse_ini_file(__DIR__.'commande.db.ini'));
        $db->setAsGlobal();
        $db->bootEloquent();
    }
}