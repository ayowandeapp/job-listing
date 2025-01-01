<?php

namespace App\Config;

use Framework\App;
use Framework\Middleware\AuthRequiredMiddleware;
use Framework\Middleware\GuestOnlyMiddleware;
use Framework\Router;

function registerMiddleware(Router $router)
{
    $router->addMiddleware(['auth' => AuthRequiredMiddleware::class]);
    $router->addMiddleware(['guest' => GuestOnlyMiddleware::class]);
}