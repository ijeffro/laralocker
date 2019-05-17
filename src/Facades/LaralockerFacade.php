<?php

namespace Ijeffro\Laralocker;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ijeffro\Laralocker\LaralockerFacade\LaralockerFacadeClass
 */
class LaralockerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laralocker';
    }
}
