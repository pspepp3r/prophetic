<?php
declare(strict_types=1);
use Src\Enums\AppEnvironment;

$appEnv = $_ENV['APP_ENV'] ?? AppEnvironment::Production->value;

return [
  'db' => [
    'app_env' => $_ENV['APP_ENV'],
    'dbname' => $_ENV['DB_DATABASE'],
    'user' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => $_ENV['DB_HOST'],
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
  ],
  'app' => [
    'app_name' => $_ENV['APP_NAME'],
    'app_version' => $_ENV['APP_VERSION'] ?? '1.0',
    'app_environment' => $appEnv,
    'app_debug' => (bool) ($_ENV['APP_DEBUG'] ?? 0)
  ],
  'error_handling' => [
    'log_errors' => true,
    'display_error_details' => true,
    'log_error_details' => true
  ],
];
