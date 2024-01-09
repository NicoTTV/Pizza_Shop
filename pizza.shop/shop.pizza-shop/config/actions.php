<?php
return [
    \pizzashop\shop\app\actions\commandes\AccederCommandeAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\app\actions\commandes\AccederCommandeAction($container->get('commande.service'));
    },
    \pizzashop\shop\app\actions\commandes\CreerCommandeAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\app\actions\commandes\CreerCommandeAction($container->get('commande.service'));
    },
    \pizzashop\shop\app\actions\commandes\ValiderCommandeAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\app\actions\commandes\ValiderCommandeAction($container->get('commande.service'));
    },
    \pizzashop\shop\app\actions\catalogue\ListerProduitsAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\app\actions\catalogue\ListerProduitsAction($container->get('catalogue.service'));
    },
    \pizzashop\shop\app\actions\catalogue\AccederProduitAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\app\actions\catalogue\AccederProduitAction($container->get('catalogue.service'));
    },
    \pizzashop\shop\app\actions\catalogue\ListerProduitsParCategorieAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\app\actions\catalogue\ListerProduitsParCategorieAction($container->get('catalogue.service'));
    },
];