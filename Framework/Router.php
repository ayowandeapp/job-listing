<?php
namespace Framework;

class Router
{
    protected $routes = [];

    private function registerRoute(string $method, string $uri, string $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];

    }

    /**
     * Add a GET route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get(string $uri, string $controller): void
    {
        $this->registerRoute('GET', $uri, $controller);
    }


    /**
     * Add a Post route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post(string $uri, string $controller): void
    {
        $this->registerRoute('POST', $uri, $controller);
    }


    /**
     * Add a put route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put(string $uri, string $controller): void
    {
        $this->registerRoute('PUT', $uri, $controller);
    }

    /**
     * Add a delete route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function delete(string $uri, string $controller): void
    {
        $this->registerRoute('DELETE', $uri, $controller);
    }

    public function route(string $uri, string $method)
    {
        foreach ($this->routes as $key => $route) {
            if ($route['uri'] === $uri && $route['method'] == $method) {
                require basePath('App/' . $route['controller']);
                return;
            }
        }

        $this->error();

    }

    /**
     * Summary of error
     * @param int $statusCode
     * @return void
     */
    private function error(int $statusCode = 404): void
    {

        http_response_code($statusCode);
        loadView("error/$statusCode");
        exit;

    }






    // return [
    //     '/' => 'controllers/home.php',
    //     '/listings' => 'controllers/listings/index.php',
    //     '/listings/create' => 'controllers/listings/create.php',
    //     '/404' => 'controllers/error/404.php'
    // ];
//     $routes = require basePath('routes.php');

    // if (array_key_exists($uri, $routes)) {
//     require basePath($routes[$uri]);
// } else {
//     http_response_code(404);
//     require basePath($routes['/404']);
// }
}