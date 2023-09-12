<?php

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true,false,false);
return $app;
