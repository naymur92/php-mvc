<?php

namespace App\Core;

use Exception;

class Container
{
    protected $bindings = [];

    /**
     * Store the class with resolver
     *
     * @param $key
     * @param $resolver
     * @return void
     */
    public function bind($key, $resolver)
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * Resolve the class
     *
     * @param $key
     * @return void
     */
    public function resolve($key)
    {
        if (!array_key_exists($key, $this->bindings)) {
            throw new Exception("No matching binding found for {$key}");
        }

        $resolver = $this->bindings[$key];

        return call_user_func($resolver);
    }
}
