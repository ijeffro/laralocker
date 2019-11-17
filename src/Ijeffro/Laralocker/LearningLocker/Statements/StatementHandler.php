<?php

namespace Ijeffro\Laralocker\LearningLocker\Statements;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;;

class StatementHandler extends APIHandler {

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


  private $statements = '/statement';
  private $api = '/api';
  private $v2 = '/v2';

  public $statement_forwarding;

  protected $headers = [
    'Content-Type' => 'application/json'
  ];

  /**
   * Learning Locker Statement Forwarding
   *
   * @param string|null $id
   * @return ForwardingHandler
   */
  public function forwarding(string $id = null)
  {
    $statement_forwarding = new ForwardingHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($statement_forwarding) return $statement_forwarding;

    return self::statementForwarding($id ? $id : null);
  }

  /**
   * Construct the Learning Locker URL
   *
   * @param string|null $id
   * @return string
   */
  public function url(string $id = null) {
    return implode('/', [trim($this->url . $this->api . $this->v2 . $this->statements, '/'), $id ?? $id]);
  }

  /**
   * Learning Locker: Get Clients
   *
   * @param array $selected
   * @return object $response
   */
  public function get(array $selected = []) {
    try {
      $response = $this->getFromLearningLocker($this->url($this->id ?? $this->id));

      if ($selected) $response = $this->select($selected, $response);

      return $response;
    } catch (Exception $e) {
      return $e;
    }
  }

  /**
   * Learning Locker: Update Client
   *
   * @return  $response
   */
  public function update($data) {
    try {
      $response = $this->patchToLearningLocker($this->url($this->id ?? $this->id), $data);

      return $response;
    } catch (Exception $e) {
      return $e;
    }
  }

  /**
   * Learning Locker: Delete Client
   *
   * @return  $response
   */
  public function delete() {
    try {
      $response = $this->deleteFromLearningLocker($this->url($this->id));
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
  public function create($data = null) {
    try {
      $response = $this->postToLearningLocker($this->url(), $data);
      return $response;
    } catch (Exception $e) {
      Log::error('Creating Client: ' . $e, array('context' => $data));
    }
  }
}
