<?php

namespace App\Core;

class Router
{
    protected array $routes = [];
    protected array $supportedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * Register a route for any HTTP method
     *
     * @param string $method
     * @param string $uri
     * @param callable|array $callback
     * @return void
     */
    public function addRoute(string $method, string $uri, callable|array $callback): void
    {
        $method = strtoupper($method);
        if (!in_array($method, $this->supportedMethods)) {
            throw new \Exception("HTTP method $method not supported.");
        }
        $this->routes[$method][$uri] = $callback;
    }

    /**
     * Register a GET route
     *
     * @param string $uri
     * @param callable|array $callback
     * @return void
     */
    public function get(string $uri, callable|array $callback): void
    {
        $this->addRoute('GET', $uri, $callback);
    }

    /**
     * Register a POST route
     *
     * @param string $uri
     * @param callable|array $callback
     * @return void
     */
    public function post(string $uri, callable|array $callback): void
    {
        $this->addRoute('POST', $uri, $callback);
    }

    /**
     * Register a PUT route
     *
     * @param string $uri
     * @param callable|array $callback
     * @return void
     */
    public function put(string $uri, callable|array $callback): void
    {
        $this->addRoute('PUT', $uri, $callback);
    }

    /**
     * Register a DELETE route
     *
     * @param string $uri
     * @param callable|array $callback
     * @return void
     */
    public function delete(string $uri, callable|array $callback): void
    {
        $this->addRoute('DELETE', $uri, $callback);
    }

    /**
     * Resolve the current route and execute its callback
     *
     * @param Request $request
     * @return void
     */
    public function resolve(Request $request)
    {
        $method = $request->getRequestMethod();
        $uri = $request->getRequestUri();

        if (!isset($this->routes[$method])) {
            throw new \Exception("Method $method not allowed", 405);
        }

        foreach ($this->routes[$method] as $route => $callback) {
            // Static routes
            if ($route === $uri) {
                return $this->executeCallback($callback, [$request]);
            }

            // Dynamic routes
            $pattern = $this->convertToRegex($route);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return $this->executeCallback($callback, array_merge([$request], $matches));
            }
        }

        throw new \Exception("Route not found", 404);
    }


    /**
     * Convert a route with placeholders
     *
     * @param string $route
     * @return string
     */
    private function convertToRegex(string $route): string
    {
        $pattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', $route);
        return "#^" . $pattern . "$#";
    }

    /**
     * Execute a callback or controller action
     *
     * @param callable|array $callback
     * @param array $routeParams
     * @return mixed
     */
    private function executeCallback(callable|array $callback, array $routeParams = [])
    {
        if (is_array($callback)) {
            [$controllerClass, $method] = $callback;

            // Instantiate the controller
            $controller = new $controllerClass();

            // Use reflection to inspect the method's parameters
            $reflection = new \ReflectionMethod($controller, $method);
            $params = [];

            foreach ($reflection->getParameters() as $param) {
                $paramType = $param->getType();

                if ($paramType && $paramType->getName() === Request::class) {
                    // Inject the Request object manually
                    $params[] = new Request();
                } else {
                    // Inject route parameters for remaining arguments
                    $params[] = array_shift($routeParams);
                }
            }

            return call_user_func_array([$controller, $method], $params);
        }

        // For simple callable functions
        return call_user_func_array($callback, $routeParams);
    }
}
