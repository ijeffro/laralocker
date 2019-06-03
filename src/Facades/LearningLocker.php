<?php

namespace Ijeffro\LaraLocker\Facades;

use Illuminate\Support\Facades\Facade;

class LearningLocker extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'learninglocker';
    }
}
