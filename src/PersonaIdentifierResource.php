<?php

namespace Ijeffro\Laralocker;

/**
 * Persona identifiers under /api/v2/personaIdentifier.
 */
class PersonaIdentifierResource extends Resource
{
    /**
     * Create the identifier, or return the one that already has this ifi.
     *
     * @param  array  $ifi  e.g. ['key' => 'mbox', 'value' => 'mailto:jane@example.com']
     * @param  string|null  $persona  the persona to attach it to when it is created
     */
    public function upsert(array $ifi, ?string $persona = null): array
    {
        return $this->connection->send($this->connection->api(), 'POST', $this->collectionPath().'/upsert', [], array_filter([
            'ifi' => $ifi,
            'persona' => $persona,
        ]));
    }
}
