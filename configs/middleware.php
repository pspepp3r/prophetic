<?php

declare(strict_types=1);

use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Src\Middlewares\CsrfFieldsMiddleware;
use Src\Middlewares\HttpSpecializedErrorMiddleware;
use Src\Middlewares\StartSessionsMiddleware;
use Src\Middlewares\ValidationErrorMiddleware;
use Src\Middlewares\ValidationExceptionMiddleware;
use Src\Services\ConfigService;

return function (App $app) {

    $container = $app->getContainer();
    $config = $container->get(ConfigService::class);

    $app->add(MethodOverrideMiddleware::class);
    $app->add(CsrfFieldsMiddleware::class);
    $app->add('csrf');
    $app->addMiddleware(TwigMiddleware::create($app, $container
        ->get(Twig::class)));
    $app->add(ValidationExceptionMiddleware::class);
    $app->add(ValidationErrorMiddleware::class);
    $app->add(HttpSpecializedErrorMiddleware::class);
    $app->add(StartSessionsMiddleware::class);

    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware(
        (bool) $config->get('error_handling.display_error_details'),
        (bool) $config->get('error_handling.log_errors'),
        (bool) $config->get('error_handling.log_error_details'),
    );
};
