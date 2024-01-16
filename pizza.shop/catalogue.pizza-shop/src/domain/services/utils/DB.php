<?php

namespace pizzashop\catalogue\domain\services\utils;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;

class DB
{
    private $db;
    public function __construct(Container $container = null)
    {
        $this->db = new Manager();
        $this->db->setAsGlobal();
        $this->db->bootEloquent();
    }



    public function addConnection(string $file1, ?string $connexionName=null): void
    {
        $this->db->addConnection(parse_ini_file($file1), name: $connexionName);
    }
}