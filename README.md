# Ably PHP Laravel Wrapper

[![Latest Stable Version](https://poser.pugx.org/ably/ably-php-laravel/v/stable)](https://packagist.org/packages/ably/ably-php-laravel)
[![Total Downloads](https://poser.pugx.org/ably/ably-php-laravel/downloads)](https://packagist.org/packages/ably/ably-php-laravel)
[![License](https://poser.pugx.org/ably/ably-php-laravel/license)](https://packagist.org/packages/ably/ably-php-laravel)

_[Ably](https://ably.com) is the platform that powers synchronized digital experiences in realtime. Whether attending an event in a virtual venue, receiving realtime financial information, or monitoring live car performance data – consumers simply expect realtime digital experiences as standard. Ably provides a suite of APIs to build, extend, and deliver powerful digital experiences in realtime for more than 250 million devices across 80 countries each month. Organizations like Bloomberg, HubSpot, Verizon, and Hopin depend on Ably’s platform to offload the growing complexity of business-critical realtime data synchronization at global scale. For more information, see the [Ably documentation](https://ably.com/docs)._

This is a Laravel wrapper / bridge for the [Ably PHP](https://github.com/ably/ably-php) library. It provides a Facade and an injectable AblyService that both act as a wrapper for a singleton Ably instance. The instance gets its parameters automatically from your config file or environment variables. You can also use AblyFactory for creating new Ably instances with (optional) custom parameters.

The PHP client library currently targets the [Ably 1.1 client library specification](https://www.ably.com/docs/client-lib-development-guide/features/). You can jump to the '[Known Limitations](#known-limitations)' section to see the features the PHP client library does not yet support or [view our client library SDKs feature support matrix](https://www.ably.com/download/sdk-feature-support-matrix) to see the list of all the available features.

## Supported Platforms

This SDK supports PHP 7.2+ and 8.0

We regression-test the library against a selection of PHP versions (which will change over time, but usually consists of the versions that are supported upstream). Please refer to [the travis config](.travis.yml) for the set of versions that currently undergo CI testing.

We'll happily support (and investigate reported problems with) any reasonably-widely-used PHP version.
If you find any compatibility issues, please [do raise an issue](https://github.com/ably/ably-php-laravel/issues/new) in this repository or [contact Ably customer support](https://support.ably.com/) for advice.

## Known Limitations

Currently, this SDK only supports [Ably REST](https://www.ably.com/docs/rest). However, if you want to subscribe to events in PHP, you can use the [MQTT adapter](https://www.ably.com/docs/mqtt) to implement [Ably's Realtime](https://www.ably.com/docs/realtime) features in PHP. 

This SDK is *not compatible* with some of the Ably features:

| Feature |
| --- |
| [Remember fallback host during failures](https://www.ably.com/docs/realtime/usage#client-options) |
| [MsgPack Binary Protocol](https://www.ably.com/docs/realtime/usage#client-options) |

## Installation

Add this package to your project, with [Composer](https://getcomposer.org/)

```bash
composer require ably/ably-php-laravel
```

Add the service provider in `config/app.php` to the `providers` array.

```php
Ably\Laravel\AblyServiceProvider::class
```

Optionally add a reference to the facade in `config/app.php` to the `aliases` array.

```php
'Ably' => Ably\Laravel\Facades\Ably::class
```

## Configuration

After adding the service provider, run the following command to have Laravel set up a configuration file for you.

```bash
php artisan vendor:publish
```

Update the created file `config/ably.php` with your key or [other options](https://www.ably.com/docs/rest/usage#client-options). You can also set the key using an environment variable named `ABLY_KEY`.

## Usage

### Facade

The facade always returns a singleton instance created with options defined in the config file. Any methods available on an AblyRest class are available through the facade. Note that properties must be called like functions (i.e. `Ably::auth()`), this is a limitation of PHP.

```php
use Ably;

echo Ably::time(); // 1467884220000
$token = Ably::auth()->requestToken([ 'clientId' => 'client123', ]); // Ably\Models\TokenDetails
Ably::channel('testChannel')->publish('testEvent', 'testPayload', 'testClientId');
```

### Dependency injection

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

## Documentation

Visit https://www.ably.com/docs for a complete API reference and more examples.

## Release Process

This library uses [semantic versioning](http://semver.org/). For each release, the following needs to be done:

* Update the dependency on [ably-php](https://github.com/ably/ably-php) within [composer.json](./composer.json) to the latest version, commit this change and push to `origin`.
* Run [`github_changelog_generator`](https://github.com/skywinder/Github-Changelog-Generator) to automate the update of the [CHANGELOG](./CHANGELOG.md). Once the `CHANGELOG` update has completed, manually change the `Unreleased` heading and link with the current version number such as `1.0.0`. Also ensure that the `Full Changelog` link points to the new version tag instead of the `HEAD`.
* Add a tag and push to origin such as `git tag 1.0.0 && git push origin 1.0.0`.
* Visit https://github.com/ably/ably-php-laravel/tags and add release notes for the release including links to the changelog entry.
* Visit https://packagist.org/packages/ably/ably-php-laravel, log in to Packagist, and click the "Update" button.


## License

Copyright (c) 2022 Ably Real-time Ltd, Licensed under the Apache License, Version 2.0.  Refer to [LICENSE](LICENSE) for the license terms.
