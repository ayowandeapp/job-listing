<?php


use Framework\Router;
use Framework\Session;

require '../helper.php';

$app = require __DIR__ . '/../App/bootstrap.php';

//route the request
$app->run();
// require loadView('home');
// require basePath('Framework/Router.php');
// require basePath('Framework/Database.php');

//simple custom autoloader
// spl_autoload_register(function ($class) {
//     $path = basePath("Framework/$class.php");
//     if (file_exists($path)) {
//         require $path;
//     }
// });
