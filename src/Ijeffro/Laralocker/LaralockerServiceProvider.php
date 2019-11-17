<?php namespace Ijeffro\Laralocker;

use Illuminate\Support\ServiceProvider;

class LaralockerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

  /**
   * Indicates the package version
   *
   * @var string
   */
  const VERSION = '1.0';

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ijeffro/laralocker');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['learninglocker'] = $this->app->share(function ($app) {
      return new LearningLocker;
    });

    $this->app->booting(function () {
      $loader = \Illuminate\Foundation\AliasLoader::getInstance();
      $loader->alias('LearningLocker', 'Ijeffro\Laralocker\Facades\LearningLocker');
    });

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('learninglocker');
	}

}
