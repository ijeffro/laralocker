# LaraLocker = Laravel + Learning Locker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ijeffro/laralocker.svg?style=flat-square)](https://packagist.org/packages/ijeffro/laralocker)
[![Build Status](https://img.shields.io/travis/ijeffro/laralocker/master.svg?style=flat-square)](https://travis-ci.org/ijeffro/laralocker)
[![Quality Score](https://img.shields.io/scrutinizer/g/ijeffro/laralocker.svg?style=flat-square)](https://scrutinizer-ci.com/g/ijeffro/laralocker)
[![Total Downloads](https://img.shields.io/packagist/dt/ijeffro/laralocker.svg?style=flat-square)](https://packagist.org/packages/ijeffro/laralocker)

LaraLocker || A Laravel package for Learning Locker® is the most installed Learning Record Store in the world. 

## Installation

You can install the package via composer:

```bash
composer require ijeffro/laralocker
```

Add the envirnoment variables to laravel's .env

```env
LEARNING_LOCKER_URL=https://saas.learninglocker.net
LEARNING_LOCKER_KEY=91e2ed0716a19728dc5deff542b7987f59802f56
LEARNING_LOCKER_SECRET=fa2d7e9850f401d6ae98e2805ccb404c6aaa8c45
```

```bash
php artisan laralocker:install
```
## Usage

You can call Learning Locker® in various different ways


<p>Trying using the Learning Locker Facade to access the stores (LRS).</p>
```php
use LearningLocker;
```


<p>Now try Interacting with the Learning locker API</p>

``` php
LearningLocker::stores()->get();
```


Get Learning Locker stores by store id (_id).

``` php
LearningLocker::store($id)->get();
```


Create a new store in Learning Locker.

``` php
LearningLocker::store()->create($data);
```


Update a Learning Locker store by id (_id)

``` php
LearningLocker::store($id)->update($data);
```


Delete a Learning Locker store

``` php
LearningLocker::store($id)->delete();
```


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email phil.graham@ht2labs.com instead of using the issue tracker.

## Credits

- [Phil Graham](https://github.com/ijeffro)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
