<?php

namespace App\Config;
use App\Controllers\ListingController;
use App\Controllers\UserController;

// return [
//     '/' => 'controllers/home.php',
//     '/listings' => 'controllers/listings/index.php',
//     '/listings/create' => 'controllers/listings/create.php',
//     '/404' => 'controllers/error/404.php'
// ];
function registerRoutes($router)
{

    $router->get('/', 'HomeController@index');

    $router->get('/listings', [ListingController::class, 'index']);

    $router->post('/listings', [ListingController::class, 'store']);

    $router->get('/listings/create', [ListingController::class, 'create'])->addRouteMiddleware(['auth']);

    $router->get('/listings/edit/{id}', [ListingController::class, 'edit'])->addRouteMiddleware(['auth']);

    $router->get('/listings/{id}', [ListingController::class, 'show']);

    $router->put('/listings/{id}', [ListingController::class, 'update'])->addRouteMiddleware(['auth']);

    $router->delete('/listings/{id}', [ListingController::class, 'delete'])->addRouteMiddleware(['auth']);

    $router->get('/auth/register', [UserController::class, 'create'])->addRouteMiddleware(['guest']);

    $router->get('/auth/login', [UserController::class, 'login'])->addRouteMiddleware(['guest']);

    $router->post('/auth/login', [UserController::class, 'authenticate']);

    $router->post('/auth/register', [UserController::class, 'store']);
    $router->post('auth/logout', [UserController::class, 'logout'])->addRouteMiddleware(['auth']);

    // $router->get('/', 'controllers/home.php');

    // $router->get('/listings', 'controllers/listings/index.php');

    // $router->get('/listings/create', 'controllers/listings/create.php');

    // $router->get('/listing', 'controllers/listings/show.php');
}
