<?php

declare(strict_types=1);

use Slim\App;
use Src\Controllers\LandingController;

return function (App $app): void {

    // Homepage
    $app->get('/', [LandingController::class, 'index']);
    $app->get('/error', [LandingController::class, 'error']);
};
