#Ably PHP Laravel Wrapper

This is a Laravel wrapper / bridge for the [Ably PHP](https://github.com/ably/ably-php) library. It provides a Facade and an injectable AblyService that both act as a wrapper for a singleton Ably instance. The instance gets its parameters automatically from your config file or environment variables.

##Installation

Add this package to your project, with [Composer](https://getcomposer.org/)

```bash
composer require ably/ably-php-laravel
```

Add the service provider to `config/app.php` in the `providers` array.

```php
Ably\Laravel\AblyServiceProvider::class
```

Optionally add a reference to the facade in `config/app.php` to the `aliases` array.

```php
'Ably' => Ably\Laravel\Facades\Ably::class
```

##Configuration

After adding the service provider, run the following command to have Laravel set up a configuration file for you.

```bash
php artisan vendor:publish
```

Update the created file `config/ably.php` with your key or [other options](https://www.ably.io/documentation/rest/usage#client-options). You can also set the key using an environment variable named `ABLY_KEY`.

##Usage

###Facade

The facade always returns a singleton instance created with options defined in the config file. Any methods available on an AblyRest class are available through the facade. Note that properties must be called like functions (i.e. `Ably::auth()`), this is a limitation of PHP.

```php
use Ably;

echo Ably::time(); // 1467884220000
$token = Ably::auth()->requestToken([ 'clientId' => 'client123', ]); // Ably\Models\TokenDetails
Ably::channel('testChannel')->publish('testEvent', 'testPayload', 'testClientId');
```

###Dependency injection

You can use `Ably\Laravel\AblyService` instead of the facade, which acts as a 1:1 wrapper for an AblyRest singleton instance created with default options. `Ably\Laravel\AblyFactory` lets you instantiate new AblyRest instances with (optional) custom options.

```php
use Ably\Laravel\AblyService;
use Ably\Laravel\AblyFactory;

function ablyExamples(AblyService $ably, AblyFactory $ablyFactory) {
	echo $ably->time(); // 1467884220000
	echo $ably->auth->clientId; // null
	$tokenDetails = $ably->auth->requestToken([ 'clientId' => 'client123', ]); // Ably\Models\TokenDetails
	$ably->channel('testChannel')->publish('testEvent', 'testPayload', 'testClientId');

	$ablyClient = $ablyFactory->make([ 'tokenDetails' => $tokenDetails ]);
	echo $ablyClient->auth->clientId; // 'client123'
}
```

##Documentation

Visit https://www.ably.io/documentation for a complete API reference and more examples.

## License

Copyright (c) 2016 Ably Real-time Ltd, Licensed under the Apache License, Version 2.0.  Refer to [LICENSE](LICENSE) for the license terms.