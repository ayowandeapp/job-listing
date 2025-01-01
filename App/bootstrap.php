<?php

//dynmaically load classes 
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Framework\App;
use Framework\Session;

use function App\Config\registerMiddleware;
use function App\Config\registerRoutes;

//load env variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

//start session
Session::start();
//init app
$app = new App;
//register a function in the composer.json file
registerRoutes($app->router);

registerMiddleware($app->router);
/* or
$router = $app->router;
//get/register all routes
$routes = require basePath('routes.php');
 */
//get/register all middleware

return $app;