<?php

namespace Ijeffro\Laralocker\LearningLocker;

use Config;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;

use Psr7\Http\Message\RequestInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr7\Http\Message\ResponseInterface;


class Connection {

  protected $org;
  protected $url;
  protected $key;
  protected $secret;
  protected $headers;
  protected $settings;
  protected $endpoint;

  public function __construct($org = null, $settings = null) {
    // $this->org = $org;
    // $this->settings = $settings;
    // $this->endpoint = $settings[LearningLocker::URL] ?? null;
    // $this->key = $settings[LearningLocker::KEY] ?? null;
    // $this->secret = $settings[LearningLocker::SECRET] ?? null;

    $this->url = Config::get('laralocker.learning-locker.api.url') ?? null;
    $this->key = Config::get('laralocker.learning-locker.api.key') ?? null;
    $this->secret = Config::get('laralocker.learning-locker.api.secret') ?? null;

    $this->headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

  }

  protected function client() {
    return new Client();
  }

  protected function auth() {
    return [$this->key, $this->secret];
  }

  protected function headers() {
    return $this->headers;
  }
}
