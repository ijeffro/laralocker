<?php

namespace Ijeffro\Laralocker\LearningLocker\Clients;

use Log;
use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class ClientHandler extends APIHandler {

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


  private $client = '/client';
  private $api = '/api';
  private $v2 = '/v2';

  protected $headers = [
    'Accept' => 'application/json',
    'Content-Type' => 'application/json'
  ];


  /**
   * Construct the Learning Locker URL
   *
   * @param string|null $id
   * @return string
   */
  public function url(string $id = null) {
    return implode('/', [trim($this->url . $this->api . $this->v2 . $this->client, '/'), $id ?? $id]);
  }

  /**
   * Learning Locker: Get Clients
   *
   * @param array $selected
   * @return object $response
   */
  public function get(array $selected = []) {
    try {
      if ($selected) {
        return $this->select($selected,
          $this->getFromLearningLocker(
            $this->url($this->id ?? $this->id)
          )
        );
      }

      return $this->getFromLearningLocker($this->url($this->id ?? $this->id));;
    } catch (Exception $e) {
      Log::error('Getting Client: ' . $e);
    }
  }

  /**
   * Learning Locker: Update Client
   *
   * @return  $response
   */
  public function update($data) {
    try {
      return $this->patchToLearningLocker($this->url($this->id ?? $this->id), $data);;
    } catch (Exception $e) {
      Log::error('Updating Client: ' . $e, array('context' => $data));
    }
  }

  /**
   * Learning Locker: Delete Client
   *
   * @return  $response
   */
  public function delete() {
    try {
      return $this->deleteFromLearningLocker($this->url($this->id));
    } catch (Exception $e) {
      Log::error('Deleting Client: ' . $e);
    }
  }

  /**
   * Learning Locker: Create Client
   *
   * @return  $response
   */
  public function create($data = null) {
    try {
      return $this->postToLearningLocker($this->url(), $data);;
    } catch (Exception $e) {
      Log::error('Creating Client: ' . $e, array('context' => $data));
    }
  }

}
