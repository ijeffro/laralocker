<div>

<p align="center">
<a href="https://laravel.com/docs/"><img align="center" width="50px" height="56px"src="https://laravel.com/img/logomark.min.svg" alt="Laravel"></a>
&nbsp;&nbsp;&nbsp;
<a href="#"><img align="center" width="25px" height="25px" src="https://www.iconsdb.com/icons/preview/royal-blue/plus-7-xxl.png" alt="plus"></a>
&nbsp;&nbsp;&nbsp;
<a href="https://docs.learninglocker.net/"><img align="center" width="50px" height="56px" src="https://www.ht2labs.com/wp-content/uploads/2019/03/learning-locker-logo-icon.png" alt="Learning Locker"><a/>
</p>

<h1 align="center">LaraLocker</h1>

<p align="center">
<a href="https://packagist.org/packages/ijeffro/laralocker"><img src="https://img.shields.io/packagist/v/ijeffro/laralocker.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://travis-ci.org/ijeffro/laralocker"><img src="https://img.shields.io/travis/ijeffro/laralocker/master.svg?style=flat-square" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/ijeffro/laralocker"><img src="https://img.shields.io/scrutinizer/g/ijeffro/laralocker.svg?style=flat-square" alt="Quality Score"></a>
<a href="https://packagist.org/packages/ijeffro/laralocker"><img src="https://img.shields.io/packagist/dt/ijeffro/laralocker.svg?style=flat-square" alt="Total Downloads"></a>
</p>


<p align="center">A Laravel package for Learning Locker® the most installed Learning Record Store in the world. LaraLocker is a PHP Client for the Learning Locker® API with support for Laravel.</p>

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

Run the install command to add the api routes...

```bash
php artisan laralocker:install
```
## Usage

You can call Learning Locker® in various different ways


Trying using the Learning Locker Facade to access the stores (LRS).

``` php
use LearningLocker;
```


Now try Interacting with the Learning locker API.

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

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ijeffro/laralocker.svg?style=flat-square)](https://packagist.org/packages/ijeffro/laralocker)
[![Build Status](https://img.shields.io/travis/ijeffro/laralocker/master.svg?style=flat-square)](https://travis-ci.org/ijeffro/laralocker)
[![Quality Score](https://img.shields.io/scrutinizer/g/ijeffro/laralocker.svg?style=flat-square)](https://scrutinizer-ci.com/g/ijeffro/laralocker)
[![Total Downloads](https://img.shields.io/packagist/dt/ijeffro/laralocker.svg?style=flat-square)](https://packagist.org/packages/ijeffro/laralocker)

</div>
