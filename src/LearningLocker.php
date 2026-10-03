<?php

namespace Ijeffro\Laralocker;

use Illuminate\Http\Client\Factory;

/**
 * The Learning Locker® API.
 *
 * Plural methods return the list resource, singular ones take an id:
 *
 *     LearningLocker::stores()->where(['title' => 'Main'])->get();
 *     LearningLocker::store($id)->update(['title' => 'Renamed']);
 */
class LearningLocker
{
    public function __construct(protected Connection $connection) {}

    /**
     * The same API with another client's credentials.
     */
    public function connect(string $url, string $key, string $secret, int $timeout = 30): self
    {
        return new self(new Connection(app(Factory::class), $url, $key, $secret, $timeout));
    }

    /**
     * True when Learning Locker answers on /api.
     */
    public function ping(): bool
    {
        try {
            return $this->connection->send($this->connection->api(), 'GET', '') === 'OK';
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * The client these credentials belong to, with its organisation and scopes.
     * The quickest way to check a key and secret.
     */
    public function clientInfo(): array
    {
        return $this->connection->send($this->connection->api(), 'GET', 'auth/client/info');
    }

    /**
     * The LRS's xAPI "about" document, with the versions it speaks.
     */
    public function about(): array
    {
        return $this->connection->send($this->connection->xapi(), 'GET', 'about');
    }

    /**
     * Run a MongoDB aggregation pipeline over the organisation's statements.
     *
     * @param  array  $pipeline  e.g. [['$match' => ['statement.verb.id' => '...']], ['$limit' => 10]]
     * @param  array  $options  cache, maxTimeMS, maxScan
     */
    public function aggregate(array $pipeline, array $options = []): array
    {
        return $this->connection->send($this->connection->api(), 'GET', 'statements/aggregate', [
            'pipeline' => json_encode($pipeline),
        ] + $options);
    }

    /**
     * Any /api/v2 model, including Enterprise-only ones such as "journey".
     */
    public function resource(string $model, ?string $id = null): Resource
    {
        return new Resource($this->connection, $model, $id);
    }

    public function organisations(): Resource
    {
        return $this->resource('organisation');
    }

    public function organisation(?string $id = null): Resource
    {
        return $this->resource('organisation', $id);
    }

    /**
     * Stores, which Learning Locker calls LRSs.
     */
    public function stores(): Resource
    {
        return $this->resource('lrs');
    }

    public function store(?string $id = null): Resource
    {
        return $this->resource('lrs', $id);
    }

    public function clients(): Resource
    {
        return $this->resource('client');
    }

    public function client(?string $id = null): Resource
    {
        return $this->resource('client', $id);
    }

    public function users(): Resource
    {
        return $this->resource('user');
    }

    public function user(?string $id = null): Resource
    {
        return $this->resource('user', $id);
    }

    public function roles(): Resource
    {
        return $this->resource('role');
    }

    public function role(?string $id = null): Resource
    {
        return $this->resource('role', $id);
    }

    public function queries(): Resource
    {
        return $this->resource('query');
    }

    public function query(?string $id = null): Resource
    {
        return $this->resource('query', $id);
    }

    public function exports(): Resource
    {
        return $this->resource('export');
    }

    public function export(?string $id = null): Resource
    {
        return $this->resource('export', $id);
    }

    public function downloads(): Resource
    {
        return $this->resource('download');
    }

    public function download(?string $id = null): Resource
    {
        return $this->resource('download', $id);
    }

    public function dashboards(): Resource
    {
        return $this->resource('dashboard');
    }

    public function dashboard(?string $id = null): Resource
    {
        return $this->resource('dashboard', $id);
    }

    public function visualisations(): Resource
    {
        return $this->resource('visualisation');
    }

    public function visualisation(?string $id = null): Resource
    {
        return $this->resource('visualisation', $id);
    }

    public function statementForwarding(?string $id = null): Resource
    {
        return $this->resource('statementforwarding', $id);
    }

    public function personas(): Resource
    {
        return $this->resource('persona');
    }

    public function persona(?string $id = null): Resource
    {
        return $this->resource('persona', $id);
    }

    public function personaIdentifiers(): PersonaIdentifierResource
    {
        return new PersonaIdentifierResource($this->connection, 'personaIdentifier', null, 'personaidentifier');
    }

    public function personaIdentifier(?string $id = null): PersonaIdentifierResource
    {
        return new PersonaIdentifierResource($this->connection, 'personaIdentifier', $id, 'personaidentifier');
    }

    public function personaAttributes(): Resource
    {
        return $this->resource('personaattribute');
    }

    public function personaAttribute(?string $id = null): Resource
    {
        return $this->resource('personaattribute', $id);
    }

    /**
     * CSV imports of persona identifiers and attributes.
     */
    public function personaImports(): Resource
    {
        return $this->resource('personasimport');
    }

    public function personaImport(?string $id = null): Resource
    {
        return $this->resource('personasimport', $id);
    }

    public function statements(): StatementResource
    {
        return new StatementResource($this->connection, 'statement');
    }

    /**
     * A stored statement by its Learning Locker _id (not its xAPI id).
     */
    public function statement(?string $id = null): StatementResource
    {
        return new StatementResource($this->connection, 'statement', $id);
    }

    public function connection(): Connection
    {
        return $this->connection;
    }
}
