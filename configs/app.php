<?php
declare(strict_types=1);
use Src\Enums\AppEnvironment;

$boolean = function (mixed $value) {
  if (in_array($value, ['true', 1, '1', true, 'yes'], true)) {
    return true;
  }

  return false;
};

$appEnv = $_ENV['APP_ENV'] ?? AppEnvironment::Production->value;
$formatAppName = strtolower(str_replace(' ', '_', $_ENV['APP_NAME'])); 

return [
  'app' => [
    'app_name' => AppEnvironment::isDevelopment($appEnv),
    'app_version' => $_ENV['APP_VERSION'] ?? '1.0',
    'app_environment' => $appEnv,
    'app_debug' => (bool) ($_ENV['APP_DEBUG'] ?? 0)
  ],
  'db' => [
    'app_env' => $_ENV['APP_ENV'],
    'dbname' => $_ENV['DB_DATABASE'],
    'user' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => $_ENV['DB_HOST'],
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
  ],
  'error_handling' => [
    'log_errors' => true,
    'display_error_details' => true,
    'log_error_details' => true
  ],
  'session' => [
    'name' => "{$formatAppName}_session",
    'flash_name' => "{$formatAppName}_flash",
    'secure' => $boolean($_ENV['SESSION_SECURE'] ?? true),
    'httponly' => $boolean($_ENV['SESSION_HTTP_ONLY'] ?? true),
    'samesite' => $_ENV['SESSION_SAME_SITE'] ?? 'lax',
  ],
];
