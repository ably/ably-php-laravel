<?php
namespace Ably\Laravel;

use Ably\AblyRest;

/**
 * Instantiates AblyRest objects
 */
class AblyFactory
{
    /**
     * Make a new AblyRest client and loads default options, if necessary
     *
     * @param array|null $clientOptions Options for the created instance, if not provided
     * the default config is used.
     *
     * @return \Ably\AblyRest
     */
    public function make($clientOptions = null)
    {
        if ($clientOptions) {
            return $this->createInstance($clientOptions);
        } else {
            return $this->createInstance(config('ably'));
        }
    }

    /**
     * Creates a new AblyRest instance
     *
     * @param array|null $clientOptions
     *
     * @return \Ably\AblyRest
     */
    protected function createInstance($clientOptions)
    {
        $laravelVersion = app()->version();
        AblyRest::setLibraryFlavourString('laravel/'.$laravelVersion);
        return new AblyRest($clientOptions);
    }
}
