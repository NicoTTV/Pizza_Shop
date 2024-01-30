<?php
return [
    'displayErrorDetails' => true,
    'com.db.config' => __DIR__ . '/commande.db.ini',
    'com.db.config.name' => 'commande',
    'auth.service' => 'auth-api.pizza-shop',

    'rabbitmq.host' => 'rabbitmq.pizza-shop',
    'rabbitmq.port' => 5672,
    'rabbitmq.user' => 'commande',
    'rabbitmq.password' => 'commande',
];
