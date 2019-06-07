<?php

namespace Ijeffro\LaraLocker\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Ijeffro\LaraLocker\LaraLockerServiceProvider;

class LearningLockerCommand extends Command
{


    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'learninglocker:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Learning Locker®';

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
        return $this->info('Coming Soon');
    }
}
