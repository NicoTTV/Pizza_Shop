<?php

namespace pizzashop\src\domain\services\commande;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

$logger = new Logger('logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../../../logs', Level::Warning));

$logger->debug('debug');
$logger->warning('warning');
$logger->error('Error');