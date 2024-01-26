<?php
declare(strict_types=1);

use pizzashop\auth\api\app\actions\RefreshAction;
use pizzashop\auth\api\app\actions\SigninAction;
use pizzashop\auth\api\app\actions\SignupAction;
use pizzashop\auth\api\app\actions\ValidateAction;

return function( \Slim\App $app):void {

    $app->post('/user/signin', SigninAction::class)
        ->setName('signin');

    $app->get('/user/validate', ValidateAction::class)
        ->setName('validate');

    $app->post('/user/refresh', RefreshAction::class)
        ->setName('refresh');

    $app->post('/user/signup', SignupAction::class)
        ->setName('signup');
};