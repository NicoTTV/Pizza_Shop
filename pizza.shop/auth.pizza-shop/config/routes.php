<?php
declare(strict_types=1);

use pizzashop\auth\api\app\actions\RefreshAction;
use pizzashop\auth\api\app\actions\SigninAction;
use pizzashop\auth\api\app\actions\ValidateAction;
use pizzashop\auth\api\app\middleWare\CorsMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):void {
    $app->add(new CorsMiddleware());

    $app->post('/api/users/signin', SigninAction::class)
        ->setName('signin');

    $app->get('/api/users/validate', ValidateAction::class)
        ->setName('validate');

    $app->post('/api/users/refresh', RefreshAction::class)
        ->setName('refresh');
};