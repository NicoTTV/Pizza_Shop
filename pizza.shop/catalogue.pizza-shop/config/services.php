<?php
return [
    'catalogue.service' => function(\Psr\Container\ContainerInterface $container){
        return new \pizzashop\catalogue\domain\services\catalogue\ServiceCatalogue();
    },
];