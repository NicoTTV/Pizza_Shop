<?php
return [
    'auth.provider' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\auth\api\domain\services\auth\AuthentificationProvider();
    },

    'jwt.manager' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\auth\api\domain\services\auth\JwtManager($container->get('JWT_EXPIRES_IN_S'), $container->get('JWT_SECRET'));
    },

    'auth.service' => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\auth\api\domain\services\auth\AuthService($container->get('auth.provider'), $container->get('jwt.manager'));
    },

    \pizzashop\auth\api\app\actions\ValidateAction::class => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\auth\api\app\actions\ValidateAction($container->get('auth.service'));
    },
    \pizzashop\auth\api\app\actions\SigninAction::class => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\auth\api\app\actions\SigninAction($container->get('auth.service'));
    },
    \pizzashop\auth\api\app\actions\RefreshAction::class => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\auth\api\app\actions\RefreshAction($container->get('auth.service'));
    },

];