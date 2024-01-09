<?php
return [
    'commande.service' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\commande\domain\services\commande\ServiceCommande($container->get('catalogue.service'));
    },
    'middleware.checkIfOwner' => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\commande\app\middleWare\CheckIfOwner($container->get('commande.service'));
    },
];