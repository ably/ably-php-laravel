<?php
namespace Ably\Laravel;

use App;
use Ably\AblyRest;

/**
 * Gets the service provider and acts as a proxy to the returned instance
 */
class AblyService
{
    private $instance;

    public function __construct()
    {
        $this->instance = App::make('ably');
    }

    /**
     * Pass any method calls to $this->instance
     *
     * @return mixed
     */
    public function __call($method, $args) {
        return call_user_func_array([$this->instance, $method], $args);
    }

    /**
     * Proxy any property getters to $this->instance
     *
     * @return mixed
     */
    public function __get($name) {
        return $this->instance->$name;
    }
}