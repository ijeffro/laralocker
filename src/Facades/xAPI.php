<?php

namespace Ijeffro\LaraLocker\Facades;

use Illuminate\Support\Facades\Facade;

class XAPI extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'xapi';
    }

}
