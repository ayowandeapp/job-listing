<?php

namespace Framework;

class App
{
    public $router;

    public function __construct()
    {
        $this->router = new Router;
    }

    public function run()
    {
        // get current uri and method
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        //route the request
        $this->router->route($uri);
    }
}