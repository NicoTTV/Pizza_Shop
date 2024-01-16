<?php
declare(strict_types=1);

use pizzashop\auth\api\app\actions\RefreshAction;
use pizzashop\auth\api\app\actions\SigninAction;
use pizzashop\auth\api\app\actions\ValidateAction;

return function( \Slim\App $app):void {

    $app->post('/api/user/signin', SigninAction::class)
        ->setName('signin');

    $app->get('/api/user/validate', ValidateAction::class)
        ->setName('validate');

    $app->post('/api/user/refresh', RefreshAction::class)
        ->setName('refresh');
};