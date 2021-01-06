# PHP client library for the Harvest REST API

[![CI](https://github.com/wearerequired/harvest-api-php-client/workflows/CI/badge.svg)](https://github.com/wearerequired/harvest-api-php-client/actions?query=workflow%3ACI) [![codecov](https://codecov.io/gh/wearerequired/harvest-api-php-client/branch/master/graph/badge.svg?token=W9R3VjbmRL)](https://codecov.io/gh/wearerequired/harvest-api-php-client)

An awesome object oriented wrapper for the Harvest REST API v2, written with and for modern PHP.

## Install

Via Composer:

```
composer require wearerequired/harvest-api-php-client php-http/guzzle7-adapter "^2.0"
```

Why `php-http/guzzle7-adapter`? The library is decoupled from any HTTP messaging client with the help by [HTTPlug](http://httplug.io/).


## Basic Usage

```php
// Include Composer's autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Set up the client.
$client = new \Required\Harvest\Client();
$client->authenticate( 'account-id', 'token' );

// Do your API calls.
$currentUser = $client->currentUser()->show();

// Example request with auto paging.
$projects = $client->projects()->allWithAutoPagingIterator();
foreach ( $projects as $project ) {
	// Do something with $project. The iterator will automatically
	// fetch new entries if the end of a page is reached.
}
```

## OAuth

This library does not include an OAuth 2.0 Client. We suggest to use the [PHP League's OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client) with [our OAuth provider for Harvest](https://github.com/wearerequired/oauth2-harvest).

## License

The MIT License (MIT). Please see [license file](https://github.com/wearerequired/harvest-api-php-client/blob/master/LICENSE) for more information.

<br>

[![a required open source product - let's get in touch](https://media.required.com/images/open-source-banner.png)](https://required.com/en/lets-get-in-touch/)
