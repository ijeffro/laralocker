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
        $this->info("Installing LaraLocker...\n");

        $this->setupClient();
        // $this->setupMigrationFiles();

        // $this->info('Migrating the database tables into your application');
        // $this->call('migrate', ['--force' => $this->option('force')]);

        $composer = $this->findComposer();
        $process = new Process($composer.' dump-autoload');

        $process->setTimeout(null); // Setting timeout to null
        $process->setWorkingDirectory(base_path())->run();

        $this->addLearningLockerRouting($filesystem);

        // $this->info('Seeding data into the database');
        // $this->seed('LaraLockerDatabaseSeeder');

        $this->publishVendor();

        // $this->checkLearningLockerConnection();

        $this->info('Successfully installed LaraLocker! Enjoy Learning Locker®');
    }

    /**
     * Setup Learning Locker Environment Variables
     *
     */
    protected function setupClient()
    {
        $this->question(" << Setup Connection to Learning Locker® ");

        if ($this->confirm("Setup a single Learning Locker client with env variables?")): $this->setupEnv();

        elseif ($this->confirm("Setup a single client with database?")):

            if($this->confirm("Add new columns to an existing database table?")) {

                // $databaseTables = \DB::connection()->getDoctrineSchemaManager()->listTableNames();

                $tables = [];
                foreach ($databaseTables as $databaseTable) {
                    $tables[] = $databaseTable->getName();
                }

                $table = $this->anticipate("Which table?", $tables);
                dd($table);

                // if($this->confirm("Which database table?")) {
                //     dump("Which database table?");
                // }

            } elseif ($this->confirm("Add Learning Locker client credentials to an existing database table?")) {
                dump("Single client in the database");
            } else {
                dump("Single client in the database");
            }

        elseif ($this->confirm("Setup multiple Learning Locker client's with database?")):
            dump("database setup");

        else:

            $this->error(" << Client Setup Failed: Laralocker requires a Learning Locker url, client key and secret ");
            $this->error(" << Docs: (https://docs.learninglocker.net/http-clients/) ");

            if($this->confirm("Attempt to conigure client details?")) {
                $this->setupClient();
            }

            return $this->error(" << Setup Failed: Laralocker requires Learning Locker Client details");

        endif;
    }

    /**
     * Setup Learning Locker Environment Variables
     *
     * @return LEARNING_LOCKER_URL
     * @return LEARNING_LOCKER_KEY
     * @return LEARNING_LOCKER_SECRET
     */
    public function setupEnv()
    {
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

            if ($this->confirm('Update Learning Locker api connection?')) {
                $domain = $this->anticipate("What's the new learning Locker url?", [
                    'https://saas.learninglocker.net', 'http://saas.learninglocker.net'
                ]);
                $this->setEnv([strtoupper(LearningLockerConstants::URL) => $domain]);

                $key = $this->ask("What's the new learning Locker client key?");
                $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $key]);

                $secret = $this->secret("What's the new learning Locker client secret");
                $this->setEnv([strtoupper(LearningLockerConstants::SECRET) => $secret]);
            }

        } else {
            try {

                // Check .env for Learning Locker url status
                switch (file_exists($env)) {
                    case $url_has_variable_and_value:
                        if ($this->confirm('Update Learning Locker domain?')) {
                            $domain = $this->anticipate("What's the new learning Locker domain?", [
                                'https://saas.learninglocker.net', 'http://saas.learninglocker.net'
                            ]);
                            $this->setEnv([strtoupper(LearningLockerConstants::URL) => $domain]);
                        }
                        break;
                    case $url_has_variable_with_no_value:
                            $domain = $this->anticipate("What's your Learning Locker url?", [
                                'https://saas.learninglocker.net', 'http://saas.learninglocker.net'
                            ]);
                            $this->setEnv([strtoupper(LearningLockerConstants::URL) => $domain]);
                        break;
                    case $url_has_no_variable_and_no_value:
                            $domain = $this->anticipate("What's your Learning Locker url?", [
                                'https://saas.learninglocker.net', 'http://saas.learninglocker.net'
                            ]);
                            $this->createEnv(strtoupper(LearningLockerConstants::URL), $domain);
                        break;
                }

                // Check .env for Learning Locker client key status
                switch (file_exists($env)) {
                    case $key_has_variable_and_value:
                        if ($this->confirm('Update Learning Locker client key?')) {
                            $key = $this->ask("What's the new learning Locker client key?");
                            $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $key]);
                        }
                        break;
                    case $key_has_variable_with_no_value:
                            $key = $this->ask("What's your Learning Locker client key?");
                            $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $key]);
                        break;
                    case $key_has_no_variable_and_no_value:
                            $key = $this->ask("What's your Learning Locker client key?");
                            $this->createEnv(strtoupper(LearningLockerConstants::KEY), $key);
                        break;
                }

                // Check .env for Learning Locker client secret status
                switch (file_exists($env)) {
                    case $secret_has_variable_and_value:
                        if ($this->confirm('Update Learning Locker client secret?')) {
                            $secret = $this->secret("What's the new learning Locker client secret");
                            $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $key]);
                        }
                        break;
                    case $secret_has_variable_with_no_value:
                            $secret = $this->secret("What's your Learning Locker client secret");
                            $this->setEnv([strtoupper(LearningLockerConstants::KEY) => $secret]);
                        break;
                    case $secret_has_no_variable_and_no_value:
                            $secret = $this->secret("What's your Learning Locker client secret");
                            $this->createEnv(strtoupper(LearningLockerConstants::SECRET), $secret);
                        break;
                }

            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        $this->info(" << Connecting to Learning Locker...");

        $connectionCheck = \LearningLocker::connection()->check(env(strtoupper(LearningLockerConstants::URL)));

        if ($connectionCheck) {
            return $this->info(" << Connected to Learning Locker Succussfully");
        }

        if ($this->confirm('Try updating Learning Locker details again?')) {
            return $this->setupEnv();
        }

        return $this->error("Unable to connection with Learning Locker");
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

            if ($this->confirm('Create Laravel api routes for Learning Locker?')) {
                $this->info('Adding LearningLocker api routes to routes/api.php');
                $filesystem->append(base_path('routes/api.php'), "\n\nLearningLocker::routes();\n");
                \LearningLocker::routes();
            }
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
