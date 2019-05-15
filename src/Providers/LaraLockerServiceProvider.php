<?php
namespace LaraLocker;

use LaraLocker\LearningLocker;
use Illuminate\Support\ServiceProvider;
use LaraLocker\LearningLocker\Statements\StatementHandler;

class LaraLockerServiceProvider extends ServiceProvider
{
  public function register()
  {

    $this->app->singleton(LaraLocker::class, function () {
        return new LaraLocker();
    });
    $this->app->alias(LaraLocker::class, 'laralocker');

    // $app = $this->app;

    // $app['laralocker'] = function () {
    //   return new LaraLockerHandler;
    // };

    // $app['learninglocker'] = function () {
    //     return new LearningLockerHandler;
    // };

    // $app['xapi'] = function () {
    //     return new StatementHandler;
    // };

    // $this->loadViewsFrom(__DIR__.'../publishable/views', 'laralocker');
    // $this->publishes([
    //   __DIR__.'/path/to/views' => resource_path('/views/laralocker'),
    // ]);

    // $this->loadMigrationsFrom(__DIR__.'/path/to/migrations');
    // $this->publishes([
    //   __DIR__.'/path/to/migrations' => route_path('laralocker.php'),
    // ]);

    // $this->loadRoutesFrom(__DIR__.'../routes/laralocker.php');
    // $this->publishes([
    //   __DIR__.'/path/to/routes' => route_path('laralocker.php'),
    // ]);

    // $this->loadTranslationsFrom(__DIR__.'/path/to/translations', 'courier');
    // $this->publishes([
    //   __DIR__.'/path/to/translations' => resource_path('lang/vendor/courier'),
    // ]);


    // $this->publishes([
    //   __DIR__.'../publishable/config/single-tenant/laralocker.php' => config_path('laralocker.php'),
    // ]);

    // $this->publishes([
    //   __DIR__.'../publishable/config/multi-tenant/laralocker.php' => config_path('laralocker.php'),
    // ]);

  }
}
