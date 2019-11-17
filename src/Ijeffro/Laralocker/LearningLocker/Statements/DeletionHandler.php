<?php

namespace Ijeffro\Laralocker\LearningLocker\StatementForwarding;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class ForwardingHandler extends APIHandler {

  /**
   * Learning Locker Conection Details
   *
   * @param string|null $id
   * @param string|null $key
   * @param string|null $secret
   * @param string|null $url
   *
   * @return void
   */
  public function __construct(string $id = null, string $key = null, string $secret = null, string $url = null)
  {
    $this->id = $id ? (string) $id : null;
    $this->key = $key ? (string) $key : null;
    $this->secret = $secret ? (string) $secret : null;
    $this->url = $url ? (string) $url : null;

    parent::__construct($this->key, $this->secret, $this->url);
  }

  private $statements = '/statements';
  private $delete = '/delete';
  private $api = '/api';
  private $v2 = '/v2';

  protected $headers = [
    'Content-Type' => 'application/json',
  ];


 /**
  * Learning Locker: Request Organisation Details
  *
  * @return  $response
  */
 public function get() {
  try {
   $url = $this->url . $this->api . $this->v2 . $this->statement_forwarding . '/' . $this->id ?? $this->id;
   $response = $this->getFromLearningLocker($url);
   return $response;
  } catch (Exception $e) {
   return $e;
  }
 }

  /**
   * Learning Locker: Request Organisation Details
   *
   * @return  $response
   */
  public function delete() {
      try {
          $url = $this->url . $this->api . $this->v2 . $this->store . '/' . $this->id;
          $response = $this->deleteFromLearningLocker($url);
          return $response;
      } catch (Exception $e) {
          return $e;
      }
  }


 public function single() {

 }
}
