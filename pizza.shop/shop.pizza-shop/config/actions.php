<?php
return [
    \pizzashop\shop\app\actions\AccederCommandeAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\app\actions\AccederCommandeAction($container->get('commande.service'));
    },
    \pizzashop\shop\app\actions\CreerCommandeAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\app\actions\CreerCommandeAction($container->get('commande.service'));
    },
    \pizzashop\shop\app\actions\ValiderCommandeAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\app\actions\ValiderCommandeAction($container->get('commande.service'));
    }
];