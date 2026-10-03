<?php

namespace Ijeffro\Laralocker;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;

class LaralockerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laralocker.php', 'laralocker');

        $this->app->bind(Connection::class, fn ($app) => new Connection(
            $app->make(Factory::class),
            $app['config']->get('laralocker.url'),
            $app['config']->get('laralocker.key'),
            $app['config']->get('laralocker.secret'),
            (int) $app['config']->get('laralocker.timeout', 30),
            $app['config']->get('laralocker.xapi.version', '1.0.3'),
        ));

        $this->app->singleton(LearningLocker::class);
        $this->app->alias(LearningLocker::class, 'learninglocker');

        // A fresh builder each time, so one statement never leaks into the next.
        $this->app->bind(XAPI::class, fn ($app) => new XAPI(
            $app->make(Connection::class),
            $app['config']->get('laralocker.xapi.language', 'en-GB'),
            $app['config']->get('laralocker.xapi.homepage'),
        ));
        $this->app->alias(XAPI::class, 'xapi');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laralocker.php' => config_path('laralocker.php'),
            ], 'laralocker-config');
        }
    }
}
