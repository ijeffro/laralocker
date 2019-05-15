<?php

namespace Ijeffro\Laralocker\Providers;

use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton('laralocker', function () {
            return new Laralocker;
        });
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(Commands\InstallCommand::class);
    }

}
