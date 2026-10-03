<?php

namespace Ijeffro\Laralocker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Ijeffro\Laralocker\XAPI actor(array $actor)
 * @method static array statements(array $filters = [])
 * @method static array statement(string $id)
 * @method static array more(string $more)
 *
 * @see \Ijeffro\Laralocker\XAPI
 */
class XAPI extends Facade
{
    // Every call starts a new statement.
    protected static $cached = false;

    protected static function getFacadeAccessor(): string
    {
        return \Ijeffro\Laralocker\XAPI::class;
    }
}
