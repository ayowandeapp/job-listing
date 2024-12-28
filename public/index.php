<?php

require '../helper.php';
// require loadView('home');
require basePath('Router.php');
require basePath('Database.php');

//init router
$router = new Router;
//get routes
$routes = require basePath('routes.php');
// get current uri and method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
//route the request
$router->route($uri, $method);