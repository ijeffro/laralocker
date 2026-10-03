<?php

namespace Ijeffro\Laralocker;

use Ijeffro\Laralocker\Exceptions\LearningLockerException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

/**
 * One Learning Locker client: its URL and basic-auth key/secret.
 */
class Connection
{
    public function __construct(
        protected Factory $http,
        protected ?string $url,
        protected ?string $key,
        protected ?string $secret,
        protected int $timeout = 30,
        protected string $xapiVersion = '1.0.3',
    ) {
        $this->url = $url === null ? null : rtrim($url, '/');
    }

    /**
     * A request to the management API under {url}/api.
     */
    public function api(): PendingRequest
    {
        return $this->request('/api');
    }

    /**
     * A request to the xAPI endpoint under {url}/data/xAPI.
     */
    public function xapi(): PendingRequest
    {
        return $this->request('/data/xAPI')
            ->withHeaders(['X-Experience-API-Version' => $this->xapiVersion]);
    }

    /**
     * Send a request and return the decoded body, throwing on any 4xx/5xx.
     */
    public function send(PendingRequest $request, string $method, string $path, array $query = [], ?array $body = null): mixed
    {
        $options = array_filter(['query' => $query, 'json' => $body], fn ($value) => $value !== null && $value !== []);

        $response = $request->send($method, ltrim($path, '/'), $options);

        if ($response->failed()) {
            throw LearningLockerException::fromResponse($response, strtoupper($method), $path);
        }

        return $this->decode($response);
    }

    public function url(): ?string
    {
        return $this->url;
    }

    protected function request(string $root): PendingRequest
    {
        if (! $this->url || ! $this->key || ! $this->secret) {
            throw LearningLockerException::missingCredentials();
        }

        return $this->http
            ->baseUrl($this->url.$root.'/')
            ->withBasicAuth($this->key, $this->secret)
            ->acceptJson()
            ->asJson()
            ->timeout($this->timeout);
    }

    protected function decode(Response $response): mixed
    {
        $body = $response->body();

        if ($body === '') {
            return true;
        }

        $decoded = json_decode($body, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $body;
    }
}
