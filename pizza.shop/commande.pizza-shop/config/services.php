<?php
return [
    'commande.service' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\commande\domain\services\commande\ServiceCommande($container->get('catalogue.service'));
    },
    'catalogue.service' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\commande\domain\services\commande\ServiceCatalogue();
    },
    \pizzashop\commande\app\middleWare\CheckIfOwner::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\commande\app\middleWare\CheckIfOwner($container->get('commande.service'), $container->get('auth.service'));
    },
];