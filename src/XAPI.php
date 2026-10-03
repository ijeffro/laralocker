<?php

namespace Ijeffro\Laralocker;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Build and send xAPI statements to Learning Locker's /data/xAPI endpoint.
 *
 *     xAPI::actor(['name' => 'Jane Doe', 'email' => 'jane@example.com'])
 *         ->did('completed')
 *         ->what('https://example.com/courses/intro', 'Introduction')
 *         ->send();
 */
class XAPI
{
    /**
     * The ADL verbs: https://adlnet.gov/expapi/verbs/
     */
    public const ADL_VERBS = [
        'answered', 'asked', 'attempted', 'attended', 'commented', 'completed',
        'exited', 'experienced', 'failed', 'imported', 'initialized', 'interacted',
        'launched', 'mastered', 'passed', 'preferred', 'progressed', 'registered',
        'responded', 'resumed', 'satisfied', 'scored', 'shared', 'suspended',
        'terminated', 'voided', 'waived',
    ];

    protected array $statement = [];

    public function __construct(
        protected Connection $connection,
        protected string $language = 'en-GB',
        protected ?string $homepage = null,
    ) {}

    /**
     * Who did it. Takes ['name', 'email'], ['name', 'account'] (with the
     * configured home page), or a full xAPI agent.
     */
    public function actor(array $actor): static
    {
        $this->statement['actor'] = $this->agent($actor);

        return $this;
    }

    /**
     * What they did: an ADL verb name ("completed"), a verb IRI, or a full
     * xAPI verb.
     */
    public function did(string|array $verb, ?string $display = null): static
    {
        if (is_array($verb)) {
            $this->statement['verb'] = $verb;

            return $this;
        }

        if (in_array($verb, self::ADL_VERBS, true)) {
            $id = 'http://adlnet.gov/expapi/verbs/'.$verb;
            $display ??= $verb;
        } elseif (filter_var($verb, FILTER_VALIDATE_URL)) {
            $id = $verb;
            $display ??= basename(parse_url($verb, PHP_URL_PATH) ?: $verb);
        } else {
            throw new InvalidArgumentException("\"{$verb}\" is not an ADL verb or an IRI.");
        }

        $this->statement['verb'] = ['id' => $id, 'display' => [$this->language => $display]];

        return $this;
    }

    /**
     * What they did it to: an activity IRI with an optional name and type,
     * or a full xAPI object.
     */
    public function what(string|array $activity, ?string $name = null, ?string $type = null, ?string $description = null): static
    {
        if (is_array($activity)) {
            $this->statement['object'] = $activity;

            return $this;
        }

        $definition = array_filter([
            'name' => $name === null ? null : [$this->language => $name],
            'description' => $description === null ? null : [$this->language => $description],
            'type' => $type,
        ]);

        $this->statement['object'] = array_filter([
            'objectType' => 'Activity',
            'id' => $activity,
            'definition' => $definition ?: null,
        ]);

        return $this;
    }

    /**
     * The result, e.g. ['score' => ['scaled' => 0.8], 'success' => true, 'completion' => true].
     */
    public function scored(array $result): static
    {
        $this->statement['result'] = $result;

        return $this;
    }

    /**
     * The context, e.g. ['platform' => 'My app', 'contextActivities' => [...]].
     */
    public function context(array $context): static
    {
        $this->statement['context'] = $context;

        return $this;
    }

    public function at(DateTimeInterface|string $timestamp): static
    {
        $this->statement['timestamp'] = Carbon::parse($timestamp)->toIso8601String();

        return $this;
    }

    /**
     * The statement as an array, ready to send.
     */
    public function make(): array
    {
        foreach (['actor', 'verb', 'object'] as $part) {
            if (! isset($this->statement[$part])) {
                throw new InvalidArgumentException("This xAPI statement is missing its {$part}.");
            }
        }

        return $this->statement + ['timestamp' => Carbon::now()->toIso8601String()];
    }

    /**
     * Send the statement; returns its id.
     */
    public function send(): string
    {
        return $this->connection->send($this->connection->xapi(), 'POST', 'statements', [], $this->make())[0];
    }

    /**
     * Alias of send(), kept from earlier versions.
     */
    public function store(): string
    {
        return $this->send();
    }

    /**
     * Read statements from the LRS, e.g. ['verb' => '...', 'since' => '...', 'limit' => 10].
     * An agent may be given as an array. Returns ['statements' => [...], 'more' => '...'].
     */
    public function statements(array $filters = []): array
    {
        if (isset($filters['agent']) && is_array($filters['agent'])) {
            $filters['agent'] = json_encode($this->agent($filters['agent']));
        }

        $filters = array_map(fn ($value) => is_bool($value) ? ($value ? 'true' : 'false') : $value, $filters);

        return $this->connection->send($this->connection->xapi(), 'GET', 'statements', $filters);
    }

    /**
     * One statement by its xAPI id.
     */
    public function statement(string $id): array
    {
        return $this->connection->send($this->connection->xapi(), 'GET', 'statements', ['statementId' => $id]);
    }

    /**
     * Follow the "more" link a statements() page returned.
     */
    public function more(string $more): array
    {
        return $this->connection->send($this->connection->xapi(), 'GET', Str::after($more, '/data/xAPI/'));
    }

    protected function agent(array $actor): array
    {
        if (isset($actor['mbox']) || isset($actor['mbox_sha1sum']) || isset($actor['openid']) || isset($actor['objectType'])
            || (isset($actor['account']) && is_array($actor['account']))) {
            return ['objectType' => 'Agent'] + $actor;
        }

        if (isset($actor['email'])) {
            return array_filter([
                'objectType' => 'Agent',
                'name' => $actor['name'] ?? null,
                'mbox' => 'mailto:'.$actor['email'],
            ]);
        }

        if (isset($actor['account'])) {
            if (! $this->homepage) {
                throw new InvalidArgumentException('Account actors need laralocker.xapi.homepage (LEARNING_LOCKER_ACTOR_HOMEPAGE or APP_URL).');
            }

            return array_filter([
                'objectType' => 'Agent',
                'name' => $actor['name'] ?? null,
                'account' => ['homePage' => $this->homepage, 'name' => (string) $actor['account']],
            ]);
        }

        throw new InvalidArgumentException('An xAPI actor needs an email, an account or a full agent.');
    }
}
