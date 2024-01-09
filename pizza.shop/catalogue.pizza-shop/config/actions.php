<?php
return [
    \pizzashop\catalogue\app\actions\ListerProduitsAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\catalogue\app\actions\ListerProduitsAction($container->get('catalogue.service'));
    },
    \pizzashop\catalogue\app\actions\AccederProduitAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\catalogue\app\actions\AccederProduitAction($container->get('catalogue.service'));
    },
    \pizzashop\catalogue\app\actions\ListerProduitsParCategorieAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\catalogue\app\actions\ListerProduitsParCategorieAction($container->get('catalogue.service'));
    },
];