<?php

namespace Ijeffro\Laralocker;

use InvalidArgumentException;

/**
 * One Learning Locker model under /api/v2/{model}, e.g. "lrs" or "client".
 *
 * Learning Locker serves these with express-restify-mongoose, so a list
 * takes a JSON `query` and `sort`, plus `skip`, `limit`, `select` and
 * `populate`. Each call to the client hands back a fresh Resource, so the
 * filters set here never leak into the next request.
 */
class Resource
{
    protected array $parameters = [];

    public function __construct(
        protected Connection $connection,
        protected string $model,
        protected ?string $id = null,
        protected ?string $cursorModel = null,
    ) {}

    /**
     * Filter a list with a MongoDB query, e.g. ['title' => 'My store'].
     */
    public function where(array $query): static
    {
        $this->parameters['query'] = array_merge($this->parameters['query'] ?? [], $query);

        return $this;
    }

    /**
     * Sort a list, e.g. ['createdAt' => -1].
     */
    public function sort(array $sort): static
    {
        $this->parameters['sort'] = $sort;

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->parameters['limit'] = $limit;

        return $this;
    }

    public function skip(int $skip): static
    {
        $this->parameters['skip'] = $skip;

        return $this;
    }

    /**
     * Only return these fields, e.g. ['_id', 'title'].
     */
    public function select(array $fields): static
    {
        $this->parameters['select'] = implode(',', $fields);

        return $this;
    }

    /**
     * Expand these references, e.g. ['lrs_id'].
     */
    public function populate(array $paths): static
    {
        $this->parameters['populate'] = implode(',', $paths);

        return $this;
    }

    /**
     * The document when the resource has an id, otherwise the list.
     */
    public function get(array $select = []): array
    {
        if ($select) {
            $this->select($select);
        }

        return $this->connection->send($this->connection->api(), 'GET', $this->path(), $this->encodedParameters());
    }

    /**
     * The first document matching the filters, or null.
     */
    public function first(): ?array
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    public function count(): int
    {
        $response = $this->connection->send($this->connection->api(), 'GET', $this->collectionPath().'/count', $this->encodedParameters(['query']));

        return (int) ($response['count'] ?? $response);
    }

    public function create(array $data): array
    {
        return $this->connection->send($this->connection->api(), 'POST', $this->collectionPath(), [], $data);
    }

    public function update(array $data): array
    {
        return $this->connection->send($this->connection->api(), 'PATCH', $this->documentPath(), [], $data);
    }

    public function delete(): bool
    {
        return (bool) $this->connection->send($this->connection->api(), 'DELETE', $this->documentPath());
    }

    /**
     * A page from Learning Locker's cursor-based connection API.
     *
     * Returns ['edges' => [['cursor' => ..., 'node' => [...]], ...], 'pageInfo' => [...]].
     * Pass pageInfo.endCursor back as $after for the next page.
     */
    public function paginate(int $first = 10, ?string $after = null): array
    {
        $parameters = array_filter([
            'first' => $first,
            'after' => $after,
            'filter' => isset($this->parameters['query']) ? json_encode($this->parameters['query']) : null,
            'sort' => json_encode($this->parameters['sort'] ?? ['_id' => 1]),
        ], fn ($value) => $value !== null);

        return $this->connection->send($this->connection->api(), 'GET', 'connection/'.($this->cursorModel ?? strtolower($this->model)), $parameters);
    }

    public function id(): ?string
    {
        return $this->id;
    }

    protected function path(): string
    {
        return $this->id === null ? $this->collectionPath() : $this->documentPath();
    }

    protected function collectionPath(): string
    {
        return 'v2/'.$this->model;
    }

    protected function documentPath(): string
    {
        if ($this->id === null || $this->id === '') {
            throw new InvalidArgumentException("Learning Locker {$this->model}: pass an id to update or delete.");
        }

        return $this->collectionPath().'/'.rawurlencode($this->id);
    }

    protected function encodedParameters(?array $only = null): array
    {
        $parameters = $only === null ? $this->parameters : array_intersect_key($this->parameters, array_flip($only));

        foreach (['query', 'sort'] as $json) {
            if (isset($parameters[$json])) {
                $parameters[$json] = json_encode($parameters[$json]);
            }
        }

        return $parameters;
    }
}
