<?php

namespace App\Core;

class Router
{
    protected array $routes = [];
    protected array $supportedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * Register a route for any HTTP method.
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
     * Register a GET route.
     */
    public function get(string $uri, callable|array $callback): void
    {
        $this->addRoute('GET', $uri, $callback);
    }

    /**
     * Register a POST route.
     */
    public function post(string $uri, callable|array $callback): void
    {
        $this->addRoute('POST', $uri, $callback);
    }

    /**
     * Register a PUT route.
     */
    public function put(string $uri, callable|array $callback): void
    {
        $this->addRoute('PUT', $uri, $callback);
    }

    /**
     * Register a DELETE route.
     */
    public function delete(string $uri, callable|array $callback): void
    {
        $this->addRoute('DELETE', $uri, $callback);
    }

    /**
     * Resolve the current route and execute its callback.
     */
    public function resolve(Request $request)
    {
        $method = $request->getRequestMethod();
        $uri = $request->getRequestUri();

        // Check incoming method exist or not
        if (!isset($this->routes[$method])) {
            throw new \Exception("Method $method not allowed", 405);
        }

        // Match the route
        foreach ($this->routes[$method] as $route => $callback) {
            // for static routes
            if ($route === $uri) {
                return $this->executeCallback($callback);
            }

            // for dynamic routes
            $pattern = $this->convertToRegex($route);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return $this->executeCallback($callback, $matches);
            }
        }

        throw new \Exception("Route not found", 404);
    }

    /**
     * Convert a route with placeholders.
     */
    private function convertToRegex(string $route): string
    {
        $pattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', $route);
        return "#^" . $pattern . "$#";
    }

    /**
     * Execute a callback or controller action.
     */
    private function executeCallback(callable|array $callback, array $params = [])
    {
        if (is_array($callback)) {
            [$controllerClass, $method] = $callback;
            $controller = new $controllerClass();
            return call_user_func_array([$controller, $method], $params);
        }

        return call_user_func_array($callback, $params);
    }
}
