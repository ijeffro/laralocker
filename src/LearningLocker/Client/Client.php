<?php
namespace HT2\Integrations\LearningLocker;

use HT2\Integrations\LearningLocker\Connection;
use Exception;

class Client extends Connection {
  
  private $client = '/client';
  private $api = '/api';
  private $v2 = '/v2';
  
  private $headers = [
    'content-type' => 'application/json'
  ];


  public function check() {
    try {
      $request = $this->get();
    } catch (Exception $e) {
      return false;
    }
    return true;
  }

  public function get() {
    $url = $this->endpoint . $this->api . $this->v2 . $this->client . '/';
    $request = $this->getClient()->get($url, [
      'auth' => $this->getAuth(),
      'headers' => [
        'content-type' => 'application/json'
      ],
    ]);

    if($request->getStatusCode() === 404) {
      throw new Exception('There was a issue connecting to Learning Locker.');
    }

    $response = $request->json();

    return true;
  }
}
