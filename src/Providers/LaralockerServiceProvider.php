<?php

namespace Ijeffro\Laralocker\Providers;

use Ijeffro\Laralocker\xAPI;
use Illuminate\Filesystem\Filesystem;
use Ijeffro\Laralocker\LearningLocker;
use Illuminate\Support\ServiceProvider;
use Ijeffro\Laralocker\Commands\InstallCommand;
use Ijeffro\Laralocker\Commands\xAPICommand;
use Ijeffro\Laralocker\Commands\LearningLockerCommand;

class LaralockerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laralocker');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laralocker');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../publishable/config/laralocker.php' => config_path('laralocker.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laralocker'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laralocker'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laralocker'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            // $this->registerPublishableResources();
            $this->registerConsoleCommands();
        }


        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../../publishable/config/laralocker.php', 'laralocker');


        // Register the main class to use with the facade
        $this->app->bind('laralocker', function () {
            return new Laralocker;
        });

        // Register the main class to use with the facade
        $this->app->bind('learninglocker', function () {
            return new LearningLocker;
        });

        // Register the main class to use with the facade
        $this->app->bind('xapi', function () {
            return new xAPI;
        });
    }

    /**
     * Register Console Commands
     *
     * @return InstallCommand
     *
     */
    private function registerConsoleCommands()
    {
        $this->commands(InstallCommand::class);
        // $this->commands(xAPICommand::class);
        // $this->commands(LearningLockerCommand::class);
    }
}
