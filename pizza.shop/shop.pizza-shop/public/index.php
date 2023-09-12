<?php
declare(strict_types=1);


require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$appli = require_once __DIR__ . '/../config/bootstrap.php';

$serv = new \pizzashop\shop\domain\services\commande\ServiceCommande();
$serv->accederCommande('f669ac6c-8ea5-3800-a61d-7dfef86ba2d9');
$appli->run();
