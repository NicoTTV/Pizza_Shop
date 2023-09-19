<?php
declare(strict_types=1);


require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$appli = require_once __DIR__ . '/../config/bootstrap.php';


$appli->run();

var_dump(\pizzashop\shop\domain\entities\catalogue\Produit::findOrFail('2')->with('tailles')->first()->tailles);
