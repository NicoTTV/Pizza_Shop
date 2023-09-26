<?php
declare(strict_types=1);


use pizzashop\shop\domain\dto\commande\commandeDTO;
use pizzashop\shop\domain\dto\commande\itemDTO;
use pizzashop\shop\domain\entities\commande\Commande;

require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$appli = require_once __DIR__ . '/../config/bootstrap.php';

$servc = new \pizzashop\shop\domain\services\catalogue\ServiceCatalogue();
$serv = new \pizzashop\shop\domain\services\commande\ServiceCommande($servc);
var_dump($serv->accederCommande('112e7ee1-3e8d-37d6-89cf-be3318ad6368'));
var_dump($serv->validerCommande('112e7ee1-3e8d-37d6-89cf-be3318ad6368'));
$commande = new CommandeDTO("coucou@gmail.com", Commande::LIVRAISON_DOMICILE);
$commande->addItem(new ItemDTO(5,1,2));
$commande->addItem(new ItemDTO(6,2,1));
$commande->addItem(new ItemDTO(9,1,7));
var_dump($serv->creerCommande($commande));
$appli->run();
