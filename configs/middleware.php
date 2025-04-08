<?php

declare(strict_types=1);

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Src\Middlewares\HttpSpecializedErrorMiddleware;
use Src\Services\ConfigService;

return function (App $app) {

    $container = $app->getContainer();
    $config = $container->get(ConfigService::class);

    $app->addMiddleware(TwigMiddleware::create($app, $container
        ->get(Twig::class)));
    $app->add(HttpSpecializedErrorMiddleware::class);

    $app->addErrorMiddleware(
        (bool) $config->get('error_handling.display_error_details'),
        (bool) $config->get('error_handling.log_errors'),
        (bool) $config->get('error_handling.log_error_details'),
    );
};
