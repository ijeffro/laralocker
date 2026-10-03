<?php

namespace Ijeffro\Laralocker\Tests;

use Ijeffro\Laralocker\Facades\LearningLocker;
use Ijeffro\Laralocker\Facades\XAPI;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class XAPITest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_builds_a_statement(): void
    {
        Carbon::setTestNow('2026-10-03 12:00:00');

        $statement = XAPI::actor(['name' => 'Jane Doe', 'email' => 'jane@example.com'])
            ->did('completed')
            ->what('https://example.com/courses/intro', 'Introduction', 'http://adlnet.gov/expapi/activities/course')
            ->scored(['success' => true])
            ->make();

        $this->assertSame([
            'actor' => ['objectType' => 'Agent', 'name' => 'Jane Doe', 'mbox' => 'mailto:jane@example.com'],
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/completed', 'display' => ['en-GB' => 'completed']],
            'object' => [
                'objectType' => 'Activity',
                'id' => 'https://example.com/courses/intro',
                'definition' => ['name' => ['en-GB' => 'Introduction'], 'type' => 'http://adlnet.gov/expapi/activities/course'],
            ],
            'result' => ['success' => true],
            'timestamp' => '2026-10-03T12:00:00+00:00',
        ], $statement);
    }

    public function test_account_actors_use_the_configured_homepage(): void
    {
        $statement = XAPI::actor(['name' => 'Jane', 'account' => 42])->did('http://example.com/verbs/shared')->what('https://example.com/a')->make();

        $this->assertSame(['homePage' => 'https://app.example.com', 'name' => '42'], $statement['actor']['account']);
        $this->assertSame(['id' => 'http://example.com/verbs/shared', 'display' => ['en-GB' => 'shared']], $statement['verb']);
    }

    public function test_sends_to_the_xapi_endpoint(): void
    {
        Http::fake(['lrs.example.com/data/xAPI/statements' => Http::response(['3f1a1c5e-0000-4000-8000-000000000001'])]);

        $id = XAPI::actor(['email' => 'jane@example.com'])->did('launched')->what('https://example.com/a')->send();

        $this->assertSame('3f1a1c5e-0000-4000-8000-000000000001', $id);
        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === 'https://lrs.example.com/data/xAPI/statements'
            && $request->header('X-Experience-API-Version')[0] === '1.0.3'
            && $request['verb']['id'] === 'http://adlnet.gov/expapi/verbs/launched');
    }

    public function test_statements_do_not_leak_between_calls(): void
    {
        XAPI::actor(['email' => 'first@example.com'])->did('launched');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing its verb');

        XAPI::actor(['email' => 'second@example.com'])->what('https://example.com/a')->make();
    }

    public function test_reads_statements_with_an_agent_filter(): void
    {
        Http::fake(['*' => Http::response(['statements' => [], 'more' => ''])]);

        XAPI::statements(['agent' => ['email' => 'jane@example.com'], 'limit' => 5, 'ascending' => true]);

        Http::assertSent(function (Request $request) {
            parse_str(parse_url($request->url(), PHP_URL_QUERY), $query);

            return $query === ['agent' => '{"objectType":"Agent","mbox":"mailto:jane@example.com"}', 'limit' => '5', 'ascending' => 'true'];
        });
    }

    public function test_follows_more_links(): void
    {
        Http::fake(['*' => Http::response(['statements' => []])]);

        XAPI::more('/data/xAPI/statements?cursor=abc');

        Http::assertSent(fn (Request $request) => $request->url() === 'https://lrs.example.com/data/xAPI/statements?cursor=abc');
    }

    public function test_statement_resource_sends_batches_over_xapi(): void
    {
        Http::fake(['*' => Http::response(['id-1', 'id-2'])]);

        $ids = LearningLocker::statements()->send([['actor' => []], ['actor' => []]]);

        $this->assertSame(['id-1', 'id-2'], $ids);
        Http::assertSent(fn (Request $request) => $request->url() === 'https://lrs.example.com/data/xAPI/statements');
    }
}
