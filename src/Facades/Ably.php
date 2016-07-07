<?php

namespace Ably\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This is the Ably facade class.
 */
class Ably extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ably';
    }

    /**
     * Handle dynamic, static calls to the object
     * with the additional ability to get properties by
     * calling them like static methods. e.g. Ably::auth()
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();

        if (! $instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        if (!$args && property_exists($instance, $method)) {
        	return $instance->$method;
        }

        return call_user_func_array([$instance, $method], $args);
    }
}
