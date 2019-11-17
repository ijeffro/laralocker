<?php

namespace Ijeffro\Laralocker\LearningLocker\API;

use Config;
use GuzzleHttp\Client;

class Connection {

  protected $url;
  protected $key;
  protected $secret;
  protected $headers;

  public function __construct(string $key = null, string $secret = null, string $url = null) {

    $this->key = $key;
    $this->secret = $secret;
    $this->url = $url;

    // if ( Config::get('laralocker.laravel.tenancy') === self::SINGLE ) {
    //   $this->url = Config::get('laralocker.learning-locker.connection.url') ?? Config::get('laralocker.learning-locker.connection.url');
    //   $this->key = Config::get('laralocker.learning-locker.connection.key') ?? Config::get('laralocker.learning-locker.connection.key');
    //   $this->secret = Config::get('laralocker.learning-locker.connection.secret') ?? Config::get('laralocker.learning-locker.connection.secret');
    // }

    // $this->url = 'https://saas.learninglocker.net';
    // $this->key = '61a54fa9fad9354fa9e48fe7dd0bdbaa282b7127';
    // $this->secret = '5679a814e4078fe4475779118321f73fe433d429';


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
