# Tokens manager for Laravel.

[![Latest Stable Version](https://poser.pugx.org/lachezargrigorov/laravel-tokens-manager/v/stable)](https://packagist.org/packages/lachezargrigorov/laravel-tokens-manager)
[![Latest Unstable Version](https://poser.pugx.org/lachezargrigorov/laravel-tokens-manager/v/unstable)](https://packagist.org/packages/lachezargrigorov/laravel-tokens-manager)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/lachezargrigorov/laravel-tokens-manager/master.svg)](https://travis-ci.org/lachezargrigorov/laravel-tokens-manager)
[![Total Downloads](https://poser.pugx.org/lachezargrigorov/laravel-tokens-manager/downloads)](https://packagist.org/packages/lachezargrigorov/laravel-tokens-manager)

An easy to use tokens manager for Laravel applications. Useful in user email confirmation process and not only.

## Installation

Via Composer

``` bash
$ composer require lachezargrigorov\laravel-tokens-manager
```

If you do not run Laravel 5.5 (or higher), then add the service provider in config/app.php:

```php
\Lachezargrigorov\TokensManager\TokensManagerServiceProvider::class,
```

If you do not run Laravel 5.5 and want to use the facade, add this to your aliases in app.php:

```php
'Tokens' => \Lachezargrigorov\TokensManager\Facades\TokensManager::class,
```

## Usage

``` php
//using Facades

//1. Create token
$token = Tokens::use('default')->create(['userId' => 123]);

//2. Send confirmation url with created token to user per email 

//3. User click on confirmation  url

//4.Get the token's payload by the token in the url. 
//This will delete the token!
$payload = Tokens::use('default')->get($token);

if($payload)
{
    //token exist and not expired
    
    $userId = $payload["userId"];
    
    //validate user's email
}

//using IOC

$tokensManager = app("tokens-manager");

$token = $tokensManager->use('default')->create(['userId' => 123]);

$payload = $tokensManager->use('default')->get($token);
```

Get payload without deleting the token

``` php
$payload = Tokens::use('default')->get($token,false);
```

Token not found
``` php
$payload = Tokens::use('default')->get($token); //null
```

Delete token

``` php
$payload = Tokens::use('default')->delete($token);
```
**Expired tokens are deleted automatically on every Tokens::use call for all managers so you can't receive the payload of expired token and you don't need to delete them manually too!**

## Testing

``` bash
$ composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see  [CONTRIBUTING](CONTRIBUTING.md) , [ISSUE_TEMPLATE](ISSUE_TEMPLATE.md) and [PULL_REQUEST_TEMPLATE](PULL_REQUEST_TEMPLATE.md) for details.

## Security

If you discover any security related issues, please email lachezar@grigorov.website instead of using the issue tracker.

## Credits

- [Lachezar Grigorov](http://grigorov.website)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
