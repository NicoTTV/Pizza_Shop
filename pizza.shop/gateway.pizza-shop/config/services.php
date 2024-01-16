<?php
return [
    'middleware.check.auth' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\gateway\app\middleware\CheckAuthUser($container->get('gateway.auth'));
    },
    'action.catalog' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\gateway\app\actions\CatalogAction($container->get('gateway.catalog'));
    },
    'action.commande' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\gateway\app\actions\ShopAction($container->get('gateway.commande'));
    },
    'action.auth' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\gateway\app\actions\AuthAction($container->get('gateway.auth'));
    },
];