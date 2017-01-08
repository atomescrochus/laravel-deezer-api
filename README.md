# Interact with the Deezer Web API

[![Latest Stable Version](https://poser.pugx.org/atomescrochus/laravel-gracenote/v/stable)](https://packagist.org/packages/atomescrochus/laravel-gracenote)
[![License](https://poser.pugx.org/atomescrochus/laravel-gracenote/license)](https://packagist.org/packages/atomescrochus/laravel-gracenote)
[![Total Downloads](https://poser.pugx.org/atomescrochus/laravel-gracenote/downloads)](https://packagist.org/packages/atomescrochus/laravel-gracenote)

The `atomescrochus/laravel-deezer-api` package provide and easy way to interact with the Gracenote Web API from any Laravel >= 5.3 application.

This package is **not** usable in production yet, it is a work in progress (contribution welcomed!). It requires PHP >= `7.0`.

## Install

You can install this package via composer:

``` bash
$ composer require atomescrochus/laravel-deezer-api
```

Then you have to install the package' service provider and alias:

```php
// config/app.php
'providers' => [
    ...
    Atomescrochus\Deezer\DeezerApiServiceProvider::class,
];
```

## Usage

``` php
// $results will be an object containing a collection of results and raw response data from Deezer

// here is an example query to search Deezer's API
$deezer = new \Atomescrochus\Deezer\Deezer();

// Execute a basic keywork search
// second argument is optional, defaults to 'track'
// see http://developers.deezer.com/api/search#connections for possible search types
$results = $deezer->basicSearch("yolo", $type);

// Thees are the options you can set
$deezer->cache(120) // an integer (number of minutes), for the cache to expire, can be 0
$deezer->strictMode() // deactivate fuzzy searching on Deezer's side
```

## Tests

Incoming.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email jp@atomescroch.us instead of using the issue tracker.

## Credits

- [Jean-Philippe Murray](https://github.com/jpmurray)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
