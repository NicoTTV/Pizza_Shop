<?php
return [
    'displayErrorDetails' => true,
    'auth.db.config' => __DIR__ . '/auth.db.ini',
    'auth.db.config.name' => 'auth',
    'JWT_SECRET' => getenv('JWT_SECRET'),
    'JWT_EXPIRES_IN_S' => getenv('JWT_EXPIRES_IN_S'),
];
