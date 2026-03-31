<?php

declare(strict_types=1);

return [
    'host' => env('ISPCONFIG_HOST', 'localhost'),
    'port' => env('ISPCONFIG_PORT', 8080),
    'username' => env('ISPCONFIG_USERNAME', ''),
    'password' => env('ISPCONFIG_PASSWORD', ''),
    'verify_ssl' => env('ISPCONFIG_VERIFY_SSL', true),
    'timeout' => env('ISPCONFIG_TIMEOUT', 30),
];
