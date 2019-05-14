<?php
namespace LaraLocker;

use Illuminate\Support\Facades\Facade;

class XAPIFacade extends Facade {

    protected static function getFacadeAccessor() { 
        return 'xapi'; 
    }

}