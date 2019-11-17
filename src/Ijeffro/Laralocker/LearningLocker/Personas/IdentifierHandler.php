<?php

namespace Ijeffro\Laralocker\LearningLocker\Personas;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class IdentifierHandler extends APIHandler {

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

  private $personaIdentifier = '/personaidentifier';
  private $api = '/api';
  private $v2 = '/v2';
  private $upsert = '/upsert';

  protected $headers = [
    'Content-Type' => 'application/json',
  ];


  /**
    * Learning Locker: Request Persona Details
    *
    * @return $response
    */
  public function get() {
    try {
    $url = $this->url . $this->api . $this->v2 . $this->personaIdentifier . '/' . $this->id ?? $this->id;
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
  public function update($data) {
    try {

      if (!array_key_exists('ifi', $data)) {
        $data = ['ifi' => $data];
      }

      if (!is_null($this->id)) {
        $data['persona'] = $this->id;
      }

      $url = $this->url . $this->api . $this->v2 . $this->personaIdentifier . $this->upsert;
      $response = $this->patchToLearningLocker($url, $data);
      return $response;
    } catch (Exception $e) {
      return $e;
    }
  }


  /**
   * Learning Locker: Request Organisation Details
   *
   * @return $response
   */
  public function create($data = null) {
    try {
      if (!array_key_exists('ifi', $data)) {
        $data = ['ifi' => $data];
      }

      if (!is_null($this->id)) {
        $data['persona'] = $this->id;
      }

      $url = $this->url . $this->api . $this->v2 . $this->personaIdentifier . $this->upsert;
      $response = $this->postToLearningLocker($this->url(), $data);
      return $response;
    } catch (Exception $e) {
      \Log::info($data);
      \Log::info($e);
      return $e;
    }
  }

}
