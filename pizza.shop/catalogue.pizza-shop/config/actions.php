<?php
return [
    \pizzashop\catalogue\app\actions\catalogue\ListerProduitsAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\catalogue\app\actions\catalogue\ListerProduitsAction($container->get('catalogue.service'));
    },
    \pizzashop\catalogue\app\actions\catalogue\AccederProduitAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\catalogue\app\actions\catalogue\AccederProduitAction($container->get('catalogue.service'));
    },
    \pizzashop\catalogue\app\actions\catalogue\ListerProduitsParCategorieAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\catalogue\app\actions\catalogue\ListerProduitsParCategorieAction($container->get('catalogue.service'));
    },
];