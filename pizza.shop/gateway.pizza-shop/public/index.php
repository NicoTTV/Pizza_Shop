<?php
declare(strict_types=1);


require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$appli = require_once __DIR__ . '/../config/Bootstrap.php';

/* routes */
(require_once __DIR__ . '/../config/routes.php')($appli);



$appli->run();
