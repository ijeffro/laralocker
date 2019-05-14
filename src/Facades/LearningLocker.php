<?php
namespace LaraLocker;

use Illuminate\Support\Facades\Facade;

class LearningLockerFacade extends Facade {
    
    protected static function getFacadeAccessor() { 
        return 'learninglocker'; 
    }
}