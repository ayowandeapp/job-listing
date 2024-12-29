<?php
namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    protected $routes = [];

    private function registerRoute(string $method, string $uri, string|array $action)
    {
        if (is_string($action)) {
            if (!str_contains($action, '@')) {
                return;
            }
            [$controller, $classMethod] = explode('@', $action);

        } elseif (is_array($action)) {
            [$controller, $classMethod] = $action;
        }
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'classMethod' => $classMethod
        ];

    }

    /**
     * Add a GET route
     * @param string $uri
     * @param string|array $controller
     * @return void
     */
    public function get(string $uri, string|array $controller): void
    {
        $this->registerRoute('GET', $uri, $controller);
    }


    /**
     * Add a Post route
     * @param string $uri
     * @param string|array $controller
     * @return void
     */
    public function post(string $uri, string|array $controller): void
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

    public function route(string $uri)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        foreach ($this->routes as $key => $route) {
            //split the current uri
            $uriSegment = explode('/', trim($uri, '/')); // e.g ['listing', 2]

            $routeSegment = explode('/', trim($route['uri'], '/')); //e.g ['listing', '{id}']

            //check if no of segment matches and the method match as well
            if (
                count($uriSegment) === count($routeSegment) &&
                strtoupper($route['method'] === $requestMethod)
            ) {
                $params = [];
                $match = true;
                for ($i = 0; $i < count($uriSegment); $i++) {
                    //if uri do not match and there is no param
                    if (
                        $routeSegment[$i] !== $uriSegment[$i] &&
                        !preg_match('/\{(.+?)\}/', $routeSegment[$i])
                    ) {
                        $match = false;
                        break;

                    }

                    //check for the params and add to the params array
                    if (preg_match('/\{(.+?)\}/', $routeSegment[$i], $matches)) {
                        $params[$matches[$i]] = $uriSegment[$i];
                    }
                }

                if ($match) {
                    $controller = str_contains($route['controller'], 'App\\Controllers\\') ? $route['controller'] : "App\\Controllers\\{$route['controller']}";
                    $controllerMethod = $route['classMethod'];
                    //init the controller and call the method
                    $controllerInit = new $controller();
                    $controllerInit->$controllerMethod($params);
                    // require basePath('App/' . $route['controller']);

                    return;
                }

            }


            // if ($route['uri'] === $uri && $route['method'] == $method) {
            //     $controller = str_contains($route['controller'], 'App\\Controllers\\') ? $route['controller'] : "App\\Controllers\\{$route['controller']}";
            //     $controllerMethod = $route['classMethod'];
            //     //init the controller and call the method
            //     $controllerInit = new $controller();
            //     $controllerInit->$controllerMethod();
            //     // require basePath('App/' . $route['controller']);

            //     return;
            // }
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
        ErrorController::notFound();
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