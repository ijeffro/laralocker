<?php

namespace Ijeffro\Laralocker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Ijeffro\Laralocker\LearningLocker connect(string $url, string $key, string $secret, int $timeout = 30)
 * @method static bool ping()
 * @method static array clientInfo()
 * @method static array about()
 * @method static array aggregate(array $pipeline, array $options = [])
 * @method static \Ijeffro\Laralocker\Resource resource(string $model, ?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource organisations()
 * @method static \Ijeffro\Laralocker\Resource organisation(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource stores()
 * @method static \Ijeffro\Laralocker\Resource store(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource clients()
 * @method static \Ijeffro\Laralocker\Resource client(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource users()
 * @method static \Ijeffro\Laralocker\Resource user(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource roles()
 * @method static \Ijeffro\Laralocker\Resource role(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource queries()
 * @method static \Ijeffro\Laralocker\Resource query(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource exports()
 * @method static \Ijeffro\Laralocker\Resource export(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource downloads()
 * @method static \Ijeffro\Laralocker\Resource download(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource dashboards()
 * @method static \Ijeffro\Laralocker\Resource dashboard(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource visualisations()
 * @method static \Ijeffro\Laralocker\Resource visualisation(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource statementForwarding(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource personas()
 * @method static \Ijeffro\Laralocker\Resource persona(?string $id = null)
 * @method static \Ijeffro\Laralocker\PersonaIdentifierResource personaIdentifiers()
 * @method static \Ijeffro\Laralocker\PersonaIdentifierResource personaIdentifier(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource personaAttributes()
 * @method static \Ijeffro\Laralocker\Resource personaAttribute(?string $id = null)
 * @method static \Ijeffro\Laralocker\Resource personaImports()
 * @method static \Ijeffro\Laralocker\Resource personaImport(?string $id = null)
 * @method static \Ijeffro\Laralocker\StatementResource statements()
 * @method static \Ijeffro\Laralocker\StatementResource statement(?string $id = null)
 *
 * @see \Ijeffro\Laralocker\LearningLocker
 */
class LearningLocker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ijeffro\Laralocker\LearningLocker::class;
    }
}
