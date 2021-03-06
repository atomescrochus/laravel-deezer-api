# Search the Deezer Web API

[![Latest Stable Version](https://poser.pugx.org/atomescrochus/laravel-deezer-api/v/stable)](https://packagist.org/packages/atomescrochus/laravel-deezer-api)
[![License](https://poser.pugx.org/atomescrochus/laravel-deezer-api/license)](https://packagist.org/packages/atomescrochus/laravel-deezer-api)
[![Total Downloads](https://poser.pugx.org/atomescrochus/laravel-deezer-api/downloads)](https://packagist.org/packages/atomescrochus/laravel-deezer-api)

The `atomescrochus/laravel-deezer-api` package provide and easy way to search the Deezer Web API from any Laravel >= 5.3 application.

This package is usable in production, but should still be considered a work in progress (contribution welcomed!). It requires PHP >= `7.0`.

## Install

You can install this package via composer:

``` bash
$ composer require atomescrochus/laravel-deezer-api
```

Then you have to install the package' service provider, _unless you are running Laravel >=5.5_ (it'll use package auto-discovery) :

```php
// config/app.php
'providers' => [
    ...
    Atomescrochus\Deezer\DeezerApiServiceProvider::class,
];
```

## Usage

``` php
// here is an example query to search Deezer's API
$deezer = new \Atomescrochus\Deezer\Deezer();

// You can execute a basic search, and hope for the best
$results = $deezer->basicSearch("yolo");

// If you already have a Deezer ID, you can do thise to get the track
$results = $deezer->getTrackById(18042083); //

// But you can also execute more complex search
$results = $deezer->artist() // string
        ->album() // string
        ->track() // string
        ->label() // string
        ->minimumDuration() // int
        ->maximumDuration() // int
        ->minimumBPM() // int
        ->maximumBPM() // int
        ->search();

// These are the options you can set with every kind of call
$deezer->cache(120) // an integer (number of minutes), for the cache to expire, can be 0

// Theses are options compatible with basicSearch() and complexe searches
$deezer->strictMode() // deactivate fuzzy searching on Deezer's side
$deezer->type('track') // defaults to track if not set. 
// see http://developers.deezer.com/api/search#connections for possible search types
$deezer->order('ranking') // defaults to ranking if not set. Use lower case!
// see http://developers.deezer.com/api/search#infos for possible order
```

### Results
 
In the example above, what is returned in `$results` is an object containing: a collection of results; a count value for the results; raw response data; and the unformatted query sent to the API.

## Tests

This is basically a wrapper around the API. We're assuming the Deezer API has been tested, and we won't duplicated tests.

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
