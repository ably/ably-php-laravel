<?php
namespace Ably\Laravel;

use Ably\AblyRest;
use Ably\Utils\Miscellaneous;
use Illuminate\Support\ServiceProvider;

class AblyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/ably.php' => config_path('ably.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ably', function($app) {
            $laravelVersion = Miscellaneous::getNumeric(app()->version());
            AblyRest::setAblyAgentHeader('laravel', $laravelVersion);
            return new AblyRest(config('ably'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'ably'
        ];
    }
}