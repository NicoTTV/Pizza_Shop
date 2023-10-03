<?php
return [
    'commande.service' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\shop\domain\services\commande\ServiceCommande($container->get('catalogue.service'));
    },
    'catalogue.service' => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\shop\domain\services\catalogue\ServiceCatalogue();
    },
];