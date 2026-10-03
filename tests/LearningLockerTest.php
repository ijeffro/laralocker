<?php

namespace Ijeffro\Laralocker\Tests;

use Ijeffro\Laralocker\Exceptions\LearningLockerException;
use Ijeffro\Laralocker\Facades\LearningLocker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use LogicException;

class LearningLockerTest extends TestCase
{
    public function test_lists_stores_with_basic_auth(): void
    {
        Http::fake(['lrs.example.com/api/v2/lrs*' => Http::response([['_id' => 'a1', 'title' => 'Main']])]);

        $this->assertSame([['_id' => 'a1', 'title' => 'Main']], LearningLocker::stores()->get());

        Http::assertSent(fn (Request $request) => $request->method() === 'GET'
            && $request->url() === 'https://lrs.example.com/api/v2/lrs'
            && $request->header('Authorization')[0] === 'Basic '.base64_encode('the-key:the-secret')
            && $request->header('Accept')[0] === 'application/json');
    }

    public function test_gets_one_store_with_selected_fields(): void
    {
        Http::fake(['*' => Http::response(['_id' => 'a1'])]);

        LearningLocker::store('a1')->get(['_id', 'title']);

        Http::assertSent(fn (Request $request) => $request->url() === 'https://lrs.example.com/api/v2/lrs/a1?select=_id%2Ctitle');
    }

    public function test_encodes_query_and_sort_as_json(): void
    {
        Http::fake(['*' => Http::response([])]);

        LearningLocker::clients()->where(['title' => 'Moodle'])->sort(['createdAt' => -1])->skip(20)->limit(10)->get();

        Http::assertSent(function (Request $request) {
            parse_str(parse_url($request->url(), PHP_URL_QUERY), $query);

            return str_starts_with($request->url(), 'https://lrs.example.com/api/v2/client?')
                && $query === ['query' => '{"title":"Moodle"}', 'sort' => '{"createdAt":-1}', 'skip' => '20', 'limit' => '10'];
        });
    }

    public function test_each_call_starts_a_fresh_resource(): void
    {
        Http::fake(['*' => Http::response([])]);

        LearningLocker::store('one')->get();
        LearningLocker::store('two')->get();

        Http::assertSent(fn (Request $request) => $request->url() === 'https://lrs.example.com/api/v2/lrs/two');
    }

    public function test_creates_updates_and_deletes(): void
    {
        Http::fake([
            '*' => Http::sequence()
                ->push(['_id' => 'new'], 201)
                ->push(['_id' => 'new', 'title' => 'Renamed'])
                ->push('', 204),
        ]);

        $this->assertSame(['_id' => 'new'], LearningLocker::store()->create(['title' => 'New']));
        $this->assertSame('Renamed', LearningLocker::store('new')->update(['title' => 'Renamed'])['title']);
        $this->assertTrue(LearningLocker::store('new')->delete());

        $sent = Http::recorded()->map(fn ($pair) => [$pair[0]->method(), $pair[0]->url(), $pair[0]->data()])->all();

        $this->assertSame([
            ['POST', 'https://lrs.example.com/api/v2/lrs', ['title' => 'New']],
            ['PATCH', 'https://lrs.example.com/api/v2/lrs/new', ['title' => 'Renamed']],
            ['DELETE', 'https://lrs.example.com/api/v2/lrs/new', []],
        ], $sent);
    }

    public function test_update_without_an_id_is_refused(): void
    {
        Http::fake();

        $this->expectException(InvalidArgumentException::class);

        LearningLocker::store()->update(['title' => 'x']);
    }

    public function test_counts(): void
    {
        Http::fake(['*' => Http::response(['count' => 42])]);

        $this->assertSame(42, LearningLocker::personas()->where(['name' => 'Jane'])->count());

        Http::assertSent(fn (Request $request) => $request->url() === 'https://lrs.example.com/api/v2/persona/count?query=%7B%22name%22%3A%22Jane%22%7D');
    }

    public function test_paginates_through_the_connection_api(): void
    {
        Http::fake(['*' => Http::response(['edges' => [], 'pageInfo' => ['hasNextPage' => false]])]);

        LearningLocker::personaIdentifiers()->paginate(5, 'cursor1');

        Http::assertSent(fn (Request $request) => str_starts_with($request->url(), 'https://lrs.example.com/api/connection/personaidentifier?first=5&after=cursor1'));
    }

    public function test_model_paths_match_learning_locker(): void
    {
        Http::fake(['*' => Http::response([])]);

        foreach ([
            'organisations' => 'organisation', 'users' => 'user', 'roles' => 'role', 'queries' => 'query',
            'exports' => 'export', 'downloads' => 'download', 'dashboards' => 'dashboard',
            'visualisations' => 'visualisation', 'statementForwarding' => 'statementforwarding',
            'personaAttributes' => 'personaattribute', 'personaIdentifiers' => 'personaIdentifier',
            'statements' => 'statement', 'personaImports' => 'personasimport',
        ] as $method => $model) {
            LearningLocker::$method()->get();
            Http::assertSent(fn (Request $request) => $request->url() === "https://lrs.example.com/api/v2/{$model}");
        }
    }

    public function test_upserts_persona_identifiers(): void
    {
        Http::fake(['*' => Http::response(['identifier' => ['_id' => 'i1'], 'wasCreated' => true])]);

        LearningLocker::personaIdentifiers()->upsert(['key' => 'mbox', 'value' => 'mailto:jane@example.com'], 'p1');

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === 'https://lrs.example.com/api/v2/personaIdentifier/upsert'
            && $request->data() === ['ifi' => ['key' => 'mbox', 'value' => 'mailto:jane@example.com'], 'persona' => 'p1']);
    }

    public function test_aggregates(): void
    {
        Http::fake(['*' => Http::response([['_id' => null, 'count' => 3]])]);

        LearningLocker::aggregate([['$match' => ['active' => true]], ['$count' => 'count']], ['maxTimeMS' => 5000]);

        Http::assertSent(function (Request $request) {
            parse_str(parse_url($request->url(), PHP_URL_QUERY), $query);

            return str_starts_with($request->url(), 'https://lrs.example.com/api/statements/aggregate?')
                && $query['pipeline'] === '[{"$match":{"active":true}},{"$count":"count"}]'
                && $query['maxTimeMS'] === '5000';
        });
    }

    public function test_errors_throw_with_learning_lockers_status(): void
    {
        Http::fake(['*' => Http::response('Unauthorized', 401)]);

        try {
            LearningLocker::stores()->get();
            $this->fail('No exception thrown.');
        } catch (LearningLockerException $exception) {
            $this->assertSame(401, $exception->status());
            $this->assertStringContainsString('GET v2/lrs with 401: Unauthorized', $exception->getMessage());
        }
    }

    public function test_missing_credentials_are_reported(): void
    {
        config(['laralocker.key' => null]);
        $this->app->forgetInstance(\Ijeffro\Laralocker\LearningLocker::class);
        LearningLocker::clearResolvedInstances();

        $this->expectException(LearningLockerException::class);
        $this->expectExceptionMessage('LEARNING_LOCKER_KEY');

        LearningLocker::stores()->get();
    }

    public function test_statements_are_read_only_over_the_v2_api(): void
    {
        $this->expectException(LogicException::class);

        LearningLocker::statements()->create(['statement' => []]);
    }

    public function test_ping_and_client_info(): void
    {
        Http::fake([
            'lrs.example.com/api/' => Http::response('OK'),
            'lrs.example.com/api/auth/client/info' => Http::response(['title' => 'Laravel', 'scopes' => ['all']]),
        ]);

        $this->assertTrue(LearningLocker::ping());
        $this->assertSame('Laravel', LearningLocker::clientInfo()['title']);
    }

    public function test_connect_uses_other_credentials(): void
    {
        Http::fake(['*' => Http::response([])]);

        LearningLocker::connect('https://other.example.com', 'k2', 's2')->stores()->get();

        Http::assertSent(fn (Request $request) => $request->url() === 'https://other.example.com/api/v2/lrs'
            && $request->header('Authorization')[0] === 'Basic '.base64_encode('k2:s2'));
    }
}
