![Ably Pub/Sub PHP Laravel Header](images/LaraVELSDK-github.png)
![Latest Stable Version](https://poser.pugx.org/ably/ably-php-laravel/v/stable)
![License](https://poser.pugx.org/ably/ably-php-laravel/license)

---

# Ably Pub/Sub PHP Laravel SDK

Build any realtime experience using Ably’s Pub/Sub PHP Laravel SDK, supported on all popular platforms and frameworks.

Ably Pub/Sub provides flexible APIs that deliver features such as pub-sub messaging, message history, presence, and push notifications. Utilizing Ably’s realtime messaging platform, applications benefit from its highly performant, reliable, and scalable infrastructure.

Find out more:

* [Ably Pub/Sub docs.](https://ably.com/docs/basics)
* [Ably Pub/Sub examples.](https://ably.com/examples?product=pubsub)

---

## Getting started

Everything you need to get started with Ably:

- [Quickstart in Pub/Sub using PHP.](https://ably.com/docs/getting-started/quickstart?lang=php)

---

## Supported Platforms

Ably aims to support a wide range of platforms. If you experience any compatibility issues, open an issue in the repository or contact [Ably support](https://ably.com/support).

This SDK supports the following platforms:

| Platform | Support |
|----------|---------|
| PHP      | >= 7.2, including PHP 8.0+. See [`.travis.yml`](./.travis.yml) for tested versions. |

The PHP client library currently targets the [Ably 1.1 client library specification](https://www.ably.com/docs/client-lib-development-guide/features/).

> [!NOTE]
> See [laravel-broadcaster](https://packagist.org/packages/ably/laravel-broadcaster/), if you're using Laravel and want to support Realtime broadcasting and events.

> [!IMPORTANT]
> PHP SDK versions < 1.1.9 will be [deprecated](https://ably.com/docs/platform/deprecate/protocol-v1) from November 1, 2025.

---

## Installation

Install the package using [Composer](https://getcomposer.org/):

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
---

## Configuration

After registering the service provider, publish the configuration file using Artisan:

```bash
php artisan vendor:publish
```

Update the created file `config/ably.php` with your key or [other options](https://www.ably.com/docs/rest/usage#client-options). You can also set the key using an environment variable named `ABLY_KEY`.

### Facade

Use the Laravel facade for to access to the Ably client.

<details>
<summary>Facade usage details.</summary>

The facade always returns a singleton instance created with options defined in the config file. Any methods available on an AblyRest class are available through the facade. Due to PHP limitations, properties must be accessed as methods, for example `Ably::auth()`):

```php
use Ably;

echo Ably::time(); // 1467884220000
$token = Ably::auth()->requestToken([ 'clientId' => 'client123', ]); // Ably\Models\TokenDetails
Ably::channel('testChannel')->publish('testEvent', 'testPayload', 'testClientId');
```
</details>

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

1. Update the dependency on [ably-php](https://github.com/ably/ably-php) within [composer.json](./composer.json) to the latest version, commit this change and push to `origin`.
2. Create a new branch for the release, named like `release/1.0.0` (where `1.0.0` is what you're releasing, being the new version).
3. Run [`github_changelog_generator`](https://github.com/skywinder/Github-Changelog-Generator) to automate the update of the [CHANGELOG](./CHANGELOG.md). Once the `CHANGELOG` update has completed, manually change the `Unreleased` heading and link with the current version number such as `1.0.0`. Also ensure that the `Full Changelog` link points to the new version tag instead of the `HEAD`.
4. Commit generated [CHANGELOG.md](./CHANGELOG.md) file.
5. Make a PR against `main`.
6. Once the PR is approved, merge it into `main`.
7. Add a tag and push to origin such as `git tag 1.0.0 && git push origin 1.0.0`.
8. Visit https://github.com/ably/ably-php-laravel/tags and add release notes for the release including links to the changelog entry.
9. Visit https://packagist.org/packages/ably/ably-php-laravel, log in to Packagist, and click the "Update" button.


## License

Copyright (c) 2022 Ably Real-time Ltd, Licensed under the Apache License, Version 2.0.  Refer to [LICENSE](LICENSE) for the license terms.
