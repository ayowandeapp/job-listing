<?php


namespace App;

use App\Services\ValidatorService;
use Framework\Database;

$config = require basePath('App/Config/db.php');

//define container definitions
return [
    ValidatorService::class => fn() => new ValidatorService(),
    Database::class => fn() => new Database($config)

];