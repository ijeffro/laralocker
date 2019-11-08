<?php

namespace Ijeffro\LaraLocker\Commands;

use Laralocker;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Ijeffro\LaraLocker\LaraLockerServiceProvider;
use Ijeffro\Laralocker\LearningLocker\API\APIHandler;
use Ijeffro\Laralocker\Constants\LearningLockerConstants;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laralocker:setup';

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'laralocker:setup {--routes : Setup Learning Locker® Endpooints.}
                                             {--client : Setup a Learning Locker® Client.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup LaraLocker - Learning Locker® package for Laravel';

    const API_ROUTE = 'api.php';
    const WEB_ROUTE = 'web.php';
    const CONSOLE_ROUTE = 'console.php';
    const CHANNELS_ROUTE = 'channels.php';

    protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';
    protected $migrationsPath = __DIR__.'/../../publishable/database/migrations/';

    protected function getOptions()
    {
        return [
            ['client', null, InputOption::VALUE_NONE, 'Setup a Learning Locker® Client', null],
            ['routes', null, InputOption::VALUE_NONE, 'Setup a Learning Locker® Endpooints', null],
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
        $this->info("Setup LaraLocker - A Learning Locker® package for Laravel...\n");

        $this->setupLaravelRouting($filesystem);

        // $this->setupLearningLockerClient();

        return $this->info("Successfully setup LaraLocker! Enjoy Learning Locker®");

        // $this->call('learninglocker:client', ['--setup' => true]);
        // $this->setupMigrationFiles();

        // $this->info('Migrating the database tables into your application');
        // $this->call('migrate', ['--force' => $this->option('force')]);

        // $composer = $this->findComposer();
        // $process = new Process($composer.' dump-autoload');

        // $process->setTimeout(null); // Setting timeout to null
        // $process->setWorkingDirectory(base_path())->run();

        // $this->info('Seeding data into the database');
        // $this->seed('LaraLockerDatabaseSeeder');

        $this->publishVendor();

        // $this->checkLearningLockerConnection();
    }

    /**
     * Setup Learning Locker® Endpoints
     *
     * @param Filesystem $filesystem
     * @return void
     */
    public function setupLaravelRouting(Filesystem $filesystem) {
        if ($this->confirm("Add the default Learning Locker® endpoints to your Laravel routes file?", true)) {

            $route_files = array_slice(scandir(base_path('routes')), 2);
            $file_choice = $this->choice('Which route file?', $route_files);

            switch ($file_choice) {
                case self::API_ROUTE:

                $this->addLearningLockerRouting($filesystem, self::API_ROUTE);
            break;
                case self::WEB_ROUTE:
                $this->addLearningLockerRouting($filesystem, self::WEB_ROUTE);
            break;
                case self::CONSOLE_ROUTE:
                $this->addLearningLockerRouting($filesystem, self::CONSOLE_ROUTE);
            break;
                case self::CHANNELS_ROUTE:
                $this->addLearningLockerRouting($filesystem, self::CHANNELS_ROUTE);
            break;
            default:
                $this->error('Could not find the Laravel route file...'); echo "\n";

                if ($this->confirm("Would you like to try again?", true)) {
                    return $this->setupLaravelRouting($filesystem);
                }
            }

        }
    }


    public function setupLearningLockerClient() {
        return $this->call('learninglocker:client', ['--setup' => true]);
    }

    /**
     * Setup Learning Locker® Environment Variables
     *
     * @return LEARNING_LOCKER_URL
     * @return LEARNING_LOCKER_KEY
     * @return LEARNING_LOCKER_SECRET
     */
    public function setupEnv() {
        $env = base_path('.env');
        $filesystem = new Filesystem;
        $env_contents = $filesystem->get($env);

        $learning_locker_url = getEnv(strtoupper(LearningLockerConstants::URL));
        $learning_locker_key = getEnv(strtoupper(LearningLockerConstants::KEY));
        $learning_locker_secret = getEnv(strtoupper(LearningLockerConstants::SECRET));

        // Has variable and value
        $url_has_variable_and_value = isset($learning_locker_url) && !empty($learning_locker_url);
        $key_has_variable_and_value = isset($learning_locker_key) && !empty($learning_locker_key);
        $secret_has_variable_and_value = isset($learning_locker_secret) && !empty($learning_locker_secret);

        // Has variable with no value
        $url_has_variable_with_no_value = false !== strpos($env_contents, strtoupper(LearningLockerConstants::URL)) && empty($learning_locker_url);
        $key_has_variable_with_no_value = false !== strpos($env_contents, strtoupper(LearningLockerConstants::KEY)) && empty($learning_locker_key);
        $secret_has_variable_with_no_value = false !== strpos($env_contents, strtoupper(LearningLockerConstants::SECRET)) && empty($learning_locker_secret);

        // Has no variable and no value
        $url_has_no_variable_and_no_value = true !== strpos($env_contents, strtoupper(LearningLockerConstants::URL));
        $key_has_no_variable_and_no_value = true !== strpos($env_contents, strtoupper(LearningLockerConstants::KEY));
        $secret_has_no_variable_and_no_value = true !== strpos($env_contents, strtoupper(LearningLockerConstants::SECRET));

        // Do the env smarts
        if ($url_has_variable_and_value && $key_has_variable_and_value && $secret_has_variable_and_value) {

            if ($this->confirm('Update Learning Locker® api connection?')) {
                $domain = $this->anticipate("What's the new Learning Locker® url?", [
                    'https://saas.learninglocker.net', 'http://saas.learninglocker.net'
                ]);
                $this->setEnv([strtoupper(LearningLockerConstants::URL) => $domain]);

                $key = $this->ask("What's the new Learning Locker® client key?");
                $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $key]);

                $secret = $this->secret("What's the new Learning Locker® client secret");
                $this->setEnv([strtoupper(LearningLockerConstants::SECRET) => $secret]);
            }

        } else {
            try {

                // Check .env for Learning Locker® url status
                switch (file_exists($env)) {
                    case $url_has_variable_and_value:
                        if ($this->confirm('Update Learning Locker® domain?')) {
                            $domain = $this->anticipate("What's the new Learning Locker® domain?", [
                                'https://saas.learninglocker.net', 'http://saas.learninglocker.net'
                            ]);
                            $this->setEnv([strtoupper(LearningLockerConstants::URL) => $domain]);
                        }
                        break;
                    case $url_has_variable_with_no_value:
                            $domain = $this->anticipate("What's your Learning Locker® url?", [
                                'https://saas.learninglocker.net', 'http://saas.learninglocker.net'
                            ]);
                            $this->setEnv([strtoupper(LearningLockerConstants::URL) => $domain]);
                        break;
                    case $url_has_no_variable_and_no_value:
                            $domain = $this->anticipate("What's your Learning Locker® url?", [
                                'https://saas.learninglocker.net', 'http://saas.learninglocker.net'
                            ]);
                            $this->createEnv(strtoupper(LearningLockerConstants::URL), $domain);
                        break;
                }

                // Check .env for Learning Locker® client key status
                switch (file_exists($env)) {
                    case $key_has_variable_and_value:
                        if ($this->confirm('Update Learning Locker® client key?')) {
                            $key = $this->ask("What's the new Learning Locker® client key?");
                            $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $key]);
                        }
                        break;
                    case $key_has_variable_with_no_value:
                            $key = $this->ask("What's your Learning Locker® client key?");
                            $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $key]);
                        break;
                    case $key_has_no_variable_and_no_value:
                            $key = $this->ask("What's your Learning Locker® client key?");
                            $this->createEnv(strtoupper(LearningLockerConstants::KEY), $key);
                        break;
                }

                // Check .env for Learning Locker® client secret status
                switch (file_exists($env)) {
                    case $secret_has_variable_and_value:
                        if ($this->confirm('Update Learning Locker® client secret?')) {
                            $secret = $this->secret("What's the new Learning Locker® client secret");
                            $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $key]);
                        }
                        break;
                    case $secret_has_variable_with_no_value:
                            $secret = $this->secret("What's your Learning Locker® client secret");
                            $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $secret]);
                        break;
                    case $secret_has_no_variable_and_no_value:
                            $secret = $this->secret("What's your Learning Locker® client secret");
                            $this->createEnv(strtoupper(LearningLockerConstants::SECRET), $secret);
                        break;
                }

            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        return $this->info(" << Connecting to Learning Locker®...");

        $connectionCheck = \LearningLocker::connection()->check(env(strtoupper(LearningLockerConstants::URL)));

        if ($connectionCheck) {
            return $this->info(" << Connected to Learning Locker® Succussfully");
        }

        if ($this->confirm('Try updating Learning Locker® details again?')) {
            return $this->setupEnv();
        }

        return $this->error("Unable to connection with Learning Locker®");
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

    public function addLearningLockerRouting(Filesystem $filesystem, $type)
    {
        $routes_contents = $filesystem->get(base_path('routes/' . $type));

        if (false === strpos($routes_contents, 'LearningLocker::routes()')) {
            if ($this->confirm('Create Laravel ' . $type . ' routes?')) {
                $filesystem->append(base_path('routes/' . $type), "\nLearningLocker::routes();\n");
                $this->info('Succesfully added Learning Locker® endpoints to laravel routes/' . $type);
            }
        } else {
            return $this->line('LearningLocker::routes() were already added to ' . $type . "\n");
        }
    }

    public function publishVendor()
    {
        $tags = ['config'];
        $this->call('vendor:publish', ['--provider' => LaraLockerServiceProvider::class, '--tag' => $tags]);
    }

    public function checkLearningLockerConnection()
    {

        $this->learning_locker_api = new APIHandler;

        if ($this->learning_locker_api->check()) {
            $this->info('Successfully connected to Learning Locker®');
        } else {
           return $this->error(' << Could not connect to Learning Locker® ');
        }

    }

    /**
     * Get a Laralocker env variable
     *
     */
    public function getEnv($key = null)
    {
        $env = base_path('.env');

        if (file_exists($env) && !is_null($key)) {
            return env($key);
        }

        if (file_exists($env) && is_null($key)) {
            $env_variables = parse_ini_file($env, true, INI_SCANNER_RAW);
            return $env_variables;
        }

    }

    /**
     * Set the Laralocker env variables
     *
     */
    public function setEnv($data)
    {
        $env = base_path('.env');
        $variables = array_keys($data);

        if (file_exists($env)) {
            foreach ($variables as $variable) {
                $env_variable = $this->getEnv($variable);
                $contents = file_get_contents($env);
                $contents = str_replace(strtoupper($variable) . '=' . $env_variable, strtoupper($variable) . '=' . $data[$variable], $contents);
                file_put_contents($env, $contents);
            }
        }
    }

    /**
     * Create a Laralocker env variable
     *
     */
    public function createEnv($key, $value)
    {
        $env = base_path('.env');

        if (file_exists($env)) {

            $filesystem = new Filesystem;
            $env_contents = $filesystem->get($env);

            if (false === strpos($env_contents, $key)) {
                $filesystem->append($env, "\n" . strtoupper($key) . '=' .  strtolower($value));
                return $this->info("Created Environment variable $value successfully.");
            }
        }
    }


}
