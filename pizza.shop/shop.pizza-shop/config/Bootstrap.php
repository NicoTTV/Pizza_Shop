<?php

use DI\ContainerBuilder;
use pizzashop\shop\domain\services\utils\DB;
use Slim\Factory\AppFactory;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/services.php');
$builder->addDefinitions(__DIR__ . '/actions.php');
$c=$builder->build();
$app = AppFactory::createFromContainer($c);

$app->add(new \pizzashop\shop\app\middleWare\Cors());
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware($c->get('displayErrorDetails'),false,false);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();


$errorHandler->forceContentType('application/json');
return $app;
