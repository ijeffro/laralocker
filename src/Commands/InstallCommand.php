<?php

namespace Ijeffro\LaraLocker\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Ijeffro\LaraLocker\LaraLockerServiceProvider;
use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class InstallCommand extends Command
{

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
    protected $description = 'Install LaraLocker - Learning Locker® package for Laravel';

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production', null],
            ['with-dummy', null, InputOption::VALUE_NONE, 'Install with dummy data', null],
        ];
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
        $this->info('Installing LaraLocker...');

        // $this->info('Migrating the database tables into your application');
        // $this->call('migrate', ['--force' => $this->option('force')]);

        $composer = $this->findComposer();
        $process = new Process($composer.' dump-autoload');

        $process->setTimeout(null); // Setting timeout to null
        $process->setWorkingDirectory(base_path())->run();

        $this->addLearningLockerRouting($filesystem);

        // $this->info('Seeding data into the database');
        // $this->seed('LaraLockerDatabaseSeeder');

        $tags = ['config'];
        $this->call('vendor:publish', ['--provider' => LaraLockerServiceProvider::class, '--tag' => $tags]);


        $this->checkLearningLockerConnection();

        $this->info('Successfully installed LaraLocker! Enjoy Learning Locker®');
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

    public function addLearningLockerRouting(Filesystem $filesystem)
    {
        $routes_contents = $filesystem->get(base_path('routes/api.php'));

        if (false === strpos($routes_contents, 'LearningLocker::routes()')) {

            if ($this->confirm('Do you wish to continue?')) {
                $this->info('Adding LearningLocker api routes to routes/api.php');
                $filesystem->append(base_path('routes/api.php'), "\n\nLearningLocker::routes();\n");
                \LearningLocker::routes();
            }
        }
    }

    public function checkLearningLockerConnection()
    {
        $this->learning_locker_api = new APIHandler;

        if ($this->learning_locker_api->checkConnection()) {
            $this->info('Successfully connected to Learning Locker®');
        } else {
           return $this->error('Could not connect to Learning Locker®');
        }

    }

}
