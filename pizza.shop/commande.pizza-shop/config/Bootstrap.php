<?php

use DI\ContainerBuilder;
use pizzashop\commande\domain\services\utils\DB;
use Slim\Factory\AppFactory;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/services.php');
$builder->addDefinitions(__DIR__ . '/actions.php');
$c=$builder->build();
$app = AppFactory::createFromContainer($c);

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware($c->get('displayErrorDetails'),false,false);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();

$db = new DB();
$db->addConnection($c->get('com.db.config'), $c->get('com.db.config.name'));

$errorHandler->forceContentType('application/json');
return $app;
