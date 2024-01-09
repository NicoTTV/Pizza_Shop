<?php
declare(strict_types=1);


use pizzashop\shop\domain\dto\commande\commandeDTO;
use pizzashop\shop\domain\dto\commande\ItemDTO;
use pizzashop\shop\domain\entities\commande\Commande;

require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$appli = require_once __DIR__ . '/../config/Bootstrap.php';

/* routes */
(require_once __DIR__ . '/../config/routes.php')($appli);



$appli->run();