<?php

namespace App\Core;

class App
{
    protected static $container;

    /**
     * Take container to be set
     *
     * @param $container
     * @return void
     */
    public static function setContainer($container): void
    {
        static::$container = $container;
    }

    /**
     * Get stored container
     *
     * @return mixed
     */
    public static function container()
    {
        return static::$container;
    }

    /**
     * Bind class with resolver
     *
     * @param $key
     * @param $resolver
     * @return void
     */
    public static function bind($key, $resolver): void
    {
        static::container()->bind($key, $resolver);
    }

    /**
     * Resolve the class
     *
     * @param $key
     * @return void
     */
    public static function resolve($key)
    {
        return static::container()->resolve($key);
    }
}
