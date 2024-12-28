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
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
//route the request
$router->route($uri, $method);