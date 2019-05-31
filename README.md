## About Laralocker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ijeffro/laralocker.svg?style=flat-square)](https://packagist.org/packages/ijeffro/laralocker)
[![Build Status](https://img.shields.io/travis/ijeffro/laralocker/master.svg?style=flat-square)](https://travis-ci.org/ijeffro/laralocker)
[![Quality Score](https://img.shields.io/scrutinizer/g/ijeffro/laralocker.svg?style=flat-square)](https://scrutinizer-ci.com/g/ijeffro/laralocker)
[![Total Downloads](https://img.shields.io/packagist/dt/ijeffro/laralocker.svg?style=flat-square)](https://packagist.org/packages/ijeffro/laralocker)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require ijeffro/laralocker
```

## Usage

``` php
// Usage description here
```

### Clients

Retreive Learning Locker's API Clients:

``` php
LearningLocker::clients()->get()
```

Retreive Learning Locker's API Client by ID:

``` php
$id = '5c63fabd27cc1568a010ef54';
LearningLocker::client($id)->get()
```

Update Learning Locker's API Client by client ID:

``` php
$id = '5c63fabd27cc1568a010ef54';
$data = [
    "title" => "Laralocker",
    "isTrusted" => true
];
LearningLocker::client($id)->update($data)
```


### Users

Retreive Learning Locker's User's:

``` php
LearningLocker::clients()->get()
```

Retreive earning Locker's User's by ID:

``` php
$id = '5c63fabd27cc1568a010ef54';
LearningLocker::user($id)->get()
```

Update Learning Locker's User's by client ID:

``` php
$id = '5aa6711c8ea12f33a1e94452';
$data = ["name" => "NutMeg" ];
LearningLocker::user($id)->update($data)
```

### Testing

``` bash
composer test
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

The Laralocker package is open-sourced software licensed under the MIT license. Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
