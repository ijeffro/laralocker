<?php

namespace Ijeffro\LaraLocker\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Ijeffro\LaraLocker\Providers\LaraLockerDummyServiceProvider;
use Ijeffro\LaraLocker\Traits\Seedable;
use Ijeffro\LaraLocker\LaraLockerServiceProvider;

class InstallCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laralocker:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install LaraLocker || The Learning Locker package for Laravel';

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production', null],
            ['with-dummy', null, InputOption::VALUE_NONE, 'Install with dummy data', null],
        ];
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Publishing the LaraLocker assets, database, and config files');

        // Publish only relevant resources on install
        $tags = ['seeds'];

        $this->call('vendor:publish', ['--provider' => LaraLockerServiceProvider::class, '--tag' => $tags]);

        $this->info('Migrating the database tables into your application');
        $this->call('migrate', ['--force' => $this->option('force')]);

        $this->info('Dumping the autoloaded files and reloading all new files');

        $composer = $this->findComposer();

        $process = new Process($composer.' dump-autoload');
        $process->setTimeout(null); // Setting timeout to null to prevent installation from stopping at a certain point in time
        $process->setWorkingDirectory(base_path())->run();

        $this->info('Adding LaraLocker routes to routes/api.php');
        $routes_contents = $filesystem->get(base_path('routes/api.php'));

        if (false === strpos($routes_contents, 'LaraLocker::routes()')) {
            $filesystem->append(
                base_path('routes/api.php'),
                "\n\nRoute::group(['prefix' => config('laralocker.route.prefix')], function () {\n    LaraLocker::routes();\n});\n"
            );
        }

        \Route::group(['prefix' => config('laralocker.route.prefix')], function () {
            \LaraLocker::routes();
        });

        $this->info('Seeding data into the database');
        $this->seed('LaraLockerDatabaseSeeder');


        $this->call('vendor:publish', ['--provider' => LaraLockerServiceProvider::class, '--tag' => ['config', 'LaraLocker_avatar']]);


        $this->info('Successfully installed LaraLocker! Enjoy');
    }
}
