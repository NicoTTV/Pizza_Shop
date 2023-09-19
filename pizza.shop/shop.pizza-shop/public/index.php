<?php
declare(strict_types=1);


require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$appli = require_once __DIR__ . '/../config/bootstrap.php';

$servc = new \pizzashop\shop\domain\services\catalogue\ServiceCatalogue();
$serv = new \pizzashop\shop\domain\services\commande\ServiceCommande($servc);
var_dump($serv->accederCommande('112e7ee1-3e8d-37d6-89cf-be3318ad6368'));
var_dump($serv->validerCommande('112e7ee1-3e8d-37d6-89cf-be3318ad6368'));
$appli->run();
