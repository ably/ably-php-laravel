<?php
namespace Ably\Laravel;

use Ably\AblyRest;
use Ably\Utils\Miscellaneous;

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
     * @throws \Ably\Exceptions\AblyException
     */
    protected function createInstance($clientOptions)
    {
        $laravelVersion = Miscellaneous::getNumeric(app()->version());
        AblyRest::setAblyAgentHeader('laravel', $laravelVersion);
        return new AblyRest($clientOptions);
    }
}
