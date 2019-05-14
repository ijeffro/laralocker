<?php
namespace HT2\Integrations\LearningLocker;

use Curatr\Services\LearningLocker;
use GuzzleHttp\Client;

class Connection {
  protected $org;
  protected $settings;
  protected $endpoint;
  protected $key;
  protected $secret;

  public function __construct($org, $settings) {
    $this->org = $org;
    $this->settings = $settings;
    $this->endpoint = $settings[LearningLocker::URL] ?? null;
    $this->key = $settings[LearningLocker::KEY] ?? null;
    $this->secret = $settings[LearningLocker::SECRET] ?? null;
  }

  protected function getClient() {
    return new Client();
  }

  protected function getAuth() {
    return [$this->key, $this->secret];
  }
}
