<?php
return [
    \pizzashop\commande\app\actions\AccederCommandeAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\commande\app\actions\AccederCommandeAction($container->get('commande.service'));
    },
    \pizzashop\commande\app\actions\CreerCommandeAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\commande\app\actions\CreerCommandeAction($container->get('commande.service'));
    },
    \pizzashop\commande\app\actions\ValiderCommandeAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\commande\app\actions\ValiderCommandeAction($container->get('commande.service'), $container->get('rabbitmq.connection'));
    },
];