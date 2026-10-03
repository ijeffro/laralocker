# Changelog

All notable changes to `laralocker` are documented in this file.

## 3.0.0 - 2026-10-03

A rewrite as a plain API connector that works against Learning Locker v2+.

### Requirements

- PHP 8.2+ and Laravel 12 or 13. Support for PHP 5.6–8.1 and Laravel 4.2–11 is dropped.
- The HTTP client is now Laravel's own (`illuminate/http`). The direct `guzzlehttp/guzzle` and `lcobucci/jwt` requirements are gone.

### Fixed

- Installs on current Laravel. 1.x was capped at Laravel 6.4 and Guzzle 6, so Composer refused it on Laravel 7 and later.
- `store(1)` followed by `store(2)` no longer returns store 1. The facade cached the first resource, and its id, for the rest of the request. Every call now gets a fresh resource.
- Learning Locker errors are thrown as `LearningLockerException`. Before, Guzzle's exceptions escaped, because the `ClientException` being caught was never imported.
- The `xAPI` facade works. It relied on an `xAPIHandler` class and a TinCan library that were never shipped.
- `LearningLocker::organisations()` exists. It was declared but never defined.
- No PHP 8.2+ deprecations from dynamic properties or optional-before-required parameters.

### Changed

- Responses are decoded arrays, not JSON strings.
- Errors throw `Ijeffro\Laralocker\Exceptions\LearningLockerException`, which carries the status and response. Before, the exception object was returned.
- The config is flat: `laralocker.url`, `laralocker.key`, `laralocker.secret`, `laralocker.timeout`, `laralocker.xapi.*`. The `.env` names are unchanged.
- The service provider is `Ijeffro\Laralocker\LaralockerServiceProvider`. Package discovery registers it.
- `xAPI::...->store()` is now `send()` and returns the statement id. `store()` remains as an alias.
- `LearningLocker::statements()->create()` throws. Learning Locker refuses it, so use `statements()->send()` or the `xAPI` facade.

### Added

- `where()`, `sort()`, `skip()`, `limit()`, `select()`, `populate()`, `first()`, `count()` and `paginate()` on every resource.
- `personaIdentifiers()` (with `upsert()`), `personaAttributes()` and `personaImports()`.
- `aggregate()`, `ping()`, `clientInfo()`, `about()`, `connect()` and `resource()`.
- `xAPI::statements()`, `xAPI::statement()` and `xAPI::more()` for reading statements.
- A test suite, run on GitHub Actions.

### Removed

- The package routes and controllers (`LearningLocker::routes()`), the `laralocker:setup` command, the migrations, the models and the database, export, queue and storage config. LaraLocker is now only an API connector.
- `journeys()` / `journeyProgress()`. Journeys are a Learning Locker Enterprise feature. Use `LearningLocker::resource('journey')` if your install has them.
- The `Laralocker` facade, which did nothing.

## 1.0.0 - 2019

- Initial release.
