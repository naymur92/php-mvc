<?php

namespace App\Core;

class Request
{
    private $data = [];

    public function __construct()
    {
        $this->data = $_REQUEST;
    }

    /**
     * Get request method.
     *
     * @return string
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get request URI except request params.
     *
     * @return string
     */
    public function getRequestUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        // Remove query parameters
        $uri = strtok($uri, '?');
        // Normalize root '/' or trailing slashes
        return $uri === '/' ? '/' : rtrim($uri, '/');
    }
}
