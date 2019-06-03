<?php

namespace Ijeffro\Laralocker\Facades;

use Illuminate\Support\Facades\Facade;

class Laralocker extends Facade
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
