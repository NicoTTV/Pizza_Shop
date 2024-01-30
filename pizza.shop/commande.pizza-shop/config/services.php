<?php
return [
    'commande.service' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\commande\domain\services\commande\ServiceCommande($container->get('catalogue.service'), $container->get('rabbitmq.channel'));
    },
    'catalogue.service' => function(\Psr\Container\ContainerInterface $container) {
        return new \pizzashop\commande\domain\services\commande\ServiceCatalogue();
    },
    \pizzashop\commande\app\middleWare\CheckIfOwner::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\commande\app\middleWare\CheckIfOwner($container->get('commande.service'), $container->get('auth.service'));
    },

    \pizzashop\commande\app\actions\CommandeAuthAction::class => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\commande\app\actions\CommandeAuthAction($container->get('auth.service'));
    },
    'rabbitmq.channel' => function(\Psr\Container\ContainerInterface $container){
        return $container->get('rabbitmq.connection')->channel();
    },
    'rabbitmq.connection' => function(\Psr\Container\ContainerInterface $container){
        return new \PhpAmqpLib\Connection\AMQPStreamConnection($container->get('rabbitmq.host'), $container->get('rabbitmq.port'), $container->get('rabbitmq.user'), $container->get('rabbitmq.password'));
    },
];