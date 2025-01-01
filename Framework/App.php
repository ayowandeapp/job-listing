<?php

namespace Framework;

class App
{
    public Router $router;

    public Container $container;

    public function __construct()
    {
        $this->router = new Router;

        //load container definitions and register them for use
        $this->container = new Container();
        $this->container->addDefinitions('App/container-definitions.php');


    }

    public function run()
    {
        // get current uri and method
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        //route the request
        $this->router->route($uri, $this->container);
    }
}