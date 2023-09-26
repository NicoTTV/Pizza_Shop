<?php

use pizzashop\shop\domain\services\utils\DB;
use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true,false,false);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
DB::initConnection();

$errorHandler->forceContentType('application/json');
return $app;
