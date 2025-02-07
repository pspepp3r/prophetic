<?php
declare(strict_types=1);

use Slim\App;
use Slim\Views\Twig;
use Src\Services\ConfigService;
use Slim\Views\TwigMiddleware;

return function (App $app) {
  $container = $app->getContainer();
  $config = $container->get(ConfigService::class);

  $app->addMiddleware(TwigMiddleware::create($app, $container
    ->get(Twig::class)));

  $app->addErrorMiddleware(
    (bool) $config->get('display_error_details'),
    (bool) $config->get('log_errors'),
    (bool) $config->get('log_error_details'),
  );
};
