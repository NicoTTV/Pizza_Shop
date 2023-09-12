<?php

namespace pizzashop\src\domain\services\commande;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('logger');
$logger->pushHandler(new StreamHandler('', Logger::WARNING));

$logger->debug('debug');
$logger->warning('warning');
$logger->error('Error');