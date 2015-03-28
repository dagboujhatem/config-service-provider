# Config Service Provider
[![Build Status](https://img.shields.io/travis/dafiti/config-service-provider/master.svg?style=flat-square)](https://travis-ci.org/dafiti/config-service-provider)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/dafiti/config-service-provider/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/dafiti/config-service-provider/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/dafiti/config-service-provider/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/dafiti/config-service-provider/?branch=master)
[![HHVM](https://img.shields.io/hhvm/dafiti/config-service-provider.svg)](https://travis-ci.org/dafiti/config-service-provider)
[![Latest Stable Version](https://img.shields.io/packagist/v/dafiti/config-service-provider.svg?style=flat-square)](https://packagist.org/packages/dafiti/config-service-provider)
[![Total Downloads](https://img.shields.io/packagist/dt/dafiti/config-service-provider.svg?style=flat-square)](https://packagist.org/packages/dafiti/config-service-provider)
[![License](https://img.shields.io/packagist/l/dafiti/config-service-provider.svg?style=flat-square)](https://packagist.org/packages/dafiti/config-service-provider)

Based [Flint/Tacker](https://github.com/flint/tacker) config service provider for [Silex](http://github.com/silexphp/silex)

## Instalation
The package is available on [Packagist](http://packagist.org/packages/dafiti/config-service-provider).
Autoloading is [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) compatible.

```json
{
    "require": {
        "dafiti/config-service-provider": "dev-master"
    }
}
```


## Usage

```php
use Silex\Application;
use Dafiti\Silex\ConfigServiceProvider;

$app = new Application();
$app->register(new ConfigServiceProvider(), [
    'config.paths' => [
        __DIR__ . '/config'
    ],
    'config.cache_dir' => __DIR__ . '/data/cache'
]);

$app['config']->load('app.ini');

echo $app['config']['shop'];

```

## License

MIT License
