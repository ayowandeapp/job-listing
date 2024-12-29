<?php

use App\Controllers\ListingController;

// return [
//     '/' => 'controllers/home.php',
//     '/listings' => 'controllers/listings/index.php',
//     '/listings/create' => 'controllers/listings/create.php',
//     '/404' => 'controllers/error/404.php'
// ];

$router->get('/', 'HomeController@index');

$router->get('/listings', [ListingController::class, 'index']);

$router->post('/listings', [ListingController::class, 'store']);

$router->get('/listings/create', [ListingController::class, 'create']);

$router->get('/listing/{id}', [ListingController::class, 'show']);



// $router->get('/', 'controllers/home.php');

// $router->get('/listings', 'controllers/listings/index.php');

// $router->get('/listings/create', 'controllers/listings/create.php');

// $router->get('/listing', 'controllers/listings/show.php');
