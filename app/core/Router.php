<?php

namespace App\Core;

use App\Http\Middleware\CsrfMiddleware;
use App\Http\Middleware\Middleware;

class Router
{
    protected array $routes = [];
    protected array $supportedMethods = ['GET', 'POST', 'PUT', 'DELETE'];
    protected array $middlewares = [];


    /**
     * Register a route for any HTTP method
     *
     * @param string $method
     * @param string $uri
     * @param callable|array $callback
     * @return self
     */
    public function addRoute(string $method, string $uri, callable|array $callback): self
    {
        $method = strtoupper($method);
        if (!in_array($method, $this->supportedMethods)) {
            throw new \Exception("HTTP method $method not supported.");
        }
        $this->routes[$method][$uri] = array(
            'callback' => $callback,
            'middleware' => [],
        );

        return $this;
    }

    /**
     * Add middleware to route
     *
     * @param array $keys
     * @return self
     */
    public function only(array $keys): self
    {
        // Retrieve the last added route for the current HTTP method
        $method = array_key_last($this->routes);
        $uri = array_key_last($this->routes[$method]);

        $this->routes[$method][$uri]['middleware'] = (array) $keys;
        return $this;
    }

    /**
     * Register a GET route
     *
     * @param string $uri
     * @param callable|array $callback
     * @return self
     */
    public function get(string $uri, callable|array $callback): self
    {
        return $this->addRoute('GET', $uri, $callback);
    }

    /**
     * Register a POST route
     *
     * @param string $uri
     * @param callable|array $callback
     * @return self
     */
    public function post(string $uri, callable|array $callback): self
    {
        return $this->addRoute('POST', $uri, $callback);
    }

    /**
     * Register a PUT route
     *
     * @param string $uri
     * @param callable|array $callback
     * @return self
     */
    public function put(string $uri, callable|array $callback): self
    {
        return $this->addRoute('PUT', $uri, $callback);
    }

    /**
     * Register a DELETE route
     *
     * @param string $uri
     * @param callable|array $callback
     * @return self
     */
    public function delete(string $uri, callable|array $callback): self
    {
        return $this->addRoute('DELETE', $uri, $callback);
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

        foreach ($this->routes[$method] as $route => $item) {
            $callback = $item['callback'];
            $middleware = $item['middleware'];

            // Static routes
            if ($route === $uri) {
                $this->checkCsrf($method, $uri, $request);

                // call middleware
                if (!empty($middleware)) {
                    Middleware::resolve($middleware);
                }

                return $this->executeCallback($callback, [$request]);
            }

            // Dynamic routes
            $pattern = $this->convertToRegex($route);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                $this->checkCsrf($method, $uri, $request);

                // call middleware
                if (!empty($middleware)) {
                    Middleware::resolve($middleware);
                }

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

    /**
     * Check csrf value existing in $request
     *
     * @param string $method
     * @param string $uri
     * @param Request $request
     * @return void
     */
    private function checkCsrf(string $method, string $uri, Request $request)
    {
        $csrfExceptions = Config::get('config.csrf-exceptions');

        $csrfMiddleware = new CsrfMiddleware($csrfExceptions ?? []);

        $csrfMiddleware->verify($method, $uri, $request);
    }
}
