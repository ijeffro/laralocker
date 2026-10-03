<?php

namespace Ijeffro\Laralocker\Tests;

use Ijeffro\Laralocker\LaralockerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [LaralockerServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'LearningLocker' => \Ijeffro\Laralocker\Facades\LearningLocker::class,
            'xAPI' => \Ijeffro\Laralocker\Facades\XAPI::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('laralocker.url', 'https://lrs.example.com/');
        $app['config']->set('laralocker.key', 'the-key');
        $app['config']->set('laralocker.secret', 'the-secret');
        $app['config']->set('laralocker.xapi.homepage', 'https://app.example.com');
    }
}
