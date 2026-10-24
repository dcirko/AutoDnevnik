<?php

declare(strict_types=1);

return [
    'environment' => getenv('APP_ENV') ?: 'local',
    'database' => [
        'host' => getenv('DB_HOST') ?: 'db',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_DATABASE') ?: 'autodnevnik',
        'username' => getenv('DB_USERNAME') ?: 'autodnevnik',
        'password' => getenv('DB_PASSWORD'),
    ],
];
