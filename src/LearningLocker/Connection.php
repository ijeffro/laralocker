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
  protected $connection;

  public function __construct($key = null, $secret = null, $url = null) {
    // $this->org = $org;
    // $this->settings = $settings;
    // $this->endpoint = $settings[LearningLocker::URL] ?? null;
    // $this->key = $settings[LearningLocker::KEY] ?? null;
    // $this->secret = $settings[LearningLocker::SECRET] ?? null;

    $this->url = Config::get('laralocker.learning-locker.api.url') ? Config::get('laralocker.learning-locker.api.url') : $url;
    $this->key = Config::get('laralocker.learning-locker.api.key') ? Config::get('laralocker.learning-locker.api.key') : $key;
    $this->secret = Config::get('laralocker.learning-locker.api.secret') ? Config::get('laralocker.learning-locker.api.secret') : $secret;

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
