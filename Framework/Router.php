<?php
namespace Framework;

use App\Controllers\ErrorController;
use Exception;
use Framework\Middleware\Authorize;

class Router
{
    protected $routes = [];

    public array $middleware = [];

    public function __construct()
    {
        // $this->middleware = new Authorize;

    }

    private function registerRoute(
        string $method,
        string $uri,
        string|array $action
    ) {
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
            'classMethod' => $classMethod,
            'middleware' => []
        ];

    }

    /**
     * Add a GET route
     * @param string $uri
     * @param string|array $controller
     */
    public function get(string $uri, string|array $controller)
    {
        $this->registerRoute('GET', $uri, $controller);
        return $this;
    }


    /**
     * Add a Post route
     * @param string $uri
     * @param string|array $controller
     */
    public function post(string $uri, string|array $controller)
    {
        $this->registerRoute('POST', $uri, $controller);
        return $this;
    }


    /**
     * Add a put route
     * @param string $uri
     * @param string|array $controller
     */
    public function put(string $uri, string|array $controller)
    {
        $this->registerRoute('PUT', $uri, $controller);
        return $this;
    }

    /**
     * Add a delete route
     * @param string $uri
     * @param string|array $controller
     */
    public function delete(string $uri, string|array $controller)
    {
        $this->registerRoute('DELETE', $uri, $controller);
        return $this;
    }

    public function route(string $uri, Container $container = null)
    {
        $requestMethod = $this->determineRequestMethod();

        foreach ($this->routes as $key => $route) {
            //split the current uri
            $uriSegment = explode('/', trim($uri, '/')); // e.g ['listing', 2]

            $routeSegment = explode('/', trim($route['uri'], '/')); //e.g ['listing', '{id}']

            //check if no of segment matches and the method match as well

            if (
                count($uriSegment) !== count($routeSegment) ||
                strtoupper($route['method']) !== $requestMethod
            ) {
                continue;
            }

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
                // dd($uriSegment, $routeSegment);

                //check for the dynamic params and add to the params array
                $this->isDynamicSegment($routeSegment[$i], $uriSegment[$i], $params);
            }

            //put the controller in a function
            $action = fn() => $this->invokeController($route, $params, $container);

            if ($match) {

                foreach ($route['middleware'] as $key => $middleware) {
                    // (new Authorize)->handle($middleware);
                    //find where the $middleware = key of $this->middleware
                    $middleware = $this->middleware[$middleware];
                    $action = fn() => (new $middleware)->handle($action);
                }


                // require basePath('App/' . $route['controller']);
                $action();
                return;
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

    private function determineRequestMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        return $method;
    }

    private function isDynamicSegment(string $routeSegment, string $uriSegment, array &$params): bool
    {
        if (preg_match('/\{(.+?)\}/', $routeSegment, $matches)) {
            $params[$matches[1]] = $uriSegment;
            return true;
        }
        return false;
    }
    private function invokeController(array $route, array $params, Container $container = null): void
    {
        $controller = str_contains($route['controller'], 'App\\Controllers\\')
            ? $route['controller']
            : "App\\Controllers\\{$route['controller']}";

        $controllerMethod = $route['classMethod'];

        if (method_exists($controller, $controllerMethod)) {

            $controllerInstance = $container ? $container->resolve($controller) : $controller(); //use container to resolve dependencies
            $controllerInstance->$controllerMethod($params);
        } else {
            throw new Exception("Method {$controllerMethod} not found in {$controller}");
        }
    }

    public function addRouteMiddleware(array $middlewares)
    {
        foreach ($middlewares as $key => $middleware) {
            $routeKey = array_key_last($this->routes);
            $this->routes[$routeKey]['middleware'][] = $middleware;
        }
    }

    public function addMiddleware(array $middleware)
    {
        $this->middleware = [...$this->middleware, ...$middleware];
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