<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/services.php');
$c=$builder->build();
$app = AppFactory::createFromContainer($c);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware($c->get('displayErrorDetails'),false,false);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();

$errorHandler->forceContentType('application/json');
return $app;
