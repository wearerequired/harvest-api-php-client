# PHP client library for the Harvest REST API

[![Build Status](https://travis-ci.com/wearerequired/harvest-api-php-client.svg?branch=master)](https://travis-ci.com/wearerequired/harvest-api-php-client) [![codecov](https://codecov.io/gh/wearerequired/harvest-api-php-client/branch/master/graph/badge.svg?token=W9R3VjbmRL)](https://codecov.io/gh/wearerequired/harvest-api-php-client)

An awesome object oriented wrapper for the Harvest REST API v2, written with and for modern PHP.

## Install

Via Composer:

```
composer require wearerequired/harvest-api-php-client php-http/guzzle6-adapter "^2.0"
```

Why `php-http/guzzle6-adapter`? The library is decoupled from any HTTP messaging client with the help by [HTTPlug](http://httplug.io/).


## Basic Usage

```php
// Include Composer's autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Set up the client.
$client = new \Required\Harvest\Client();
$client->authenticate( 'account-id', 'token' );

// Do your API calls.
$currentUser = $client->currentUser()->show();
```

## OAuth

This library doesn't not include a OAuth 2.0 Client. We suggest to use the [PHP League's OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client) with our provider for Harvest.

## License

The MIT License (MIT). Please see [license file](https://github.com/wearerequired/harvest-api-php-client/blob/master/LICENSE) for more information.
