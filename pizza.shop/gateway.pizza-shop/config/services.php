<?php
return [
    \pizzashop\gateway\app\middleWare\CheckAuthUser::class => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\gateway\app\middleware\CheckAuthUser($container->get('gateway.auth'));
    },
    \pizzashop\gateway\app\actions\CatalogAction::class => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\gateway\app\actions\CatalogAction($container->get('gateway.catalog'));
    },
    \pizzashop\gateway\app\actions\ShopAction::class => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\gateway\app\actions\ShopAction($container->get('gateway.commande'));
    },
    \pizzashop\gateway\app\actions\AuthAction::class => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\gateway\app\actions\AuthAction($container->get('gateway.auth'));
    },
];