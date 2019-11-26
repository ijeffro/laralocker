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

  private $persona_identifier = '/personaidentifier';
  private $api = '/api';
  private $v2 = '/v2';
  private $upsert = '/upsert';

  protected $headers = [
    'Content-Type' => 'application/json',
  ];

  /**
   * Construct the Learning Locker URL
   *
   * @param string|integer $id
   * @return string
   */
  public function url(string $id = null) {
    return trim($this->url . $this->api . $this->v2 . $this->persona_identifier, '/') . '/' . $id ?? $id;
  }

  /**
    * Learning Locker: Request Persona Details
    *
    * @return $response
    */
  public function get() {
    try {
      $response = $this->getFromLearningLocker($this->url($this->id ?? $this->id));
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

      $url = $this->url . $this->api . $this->v2 . $this->persona_identifier . $this->upsert;

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

      $data = $this->preparePayload($data);

      $url = $this->url . $this->api . $this->v2 . $this->persona_identifier . $this->upsert;
      $response = $this->postToLearningLocker($url, $data);
      return $response;
    } catch (Exception $e) {
      \Log::info($data);
      \Log::info($e);
      return $e;
    }
  }

  public function preparePayload($data) {
    if (!array_key_exists('ifi', $data)) {
      $data = ['ifi' => $data];
    }

    if (!is_null($this->id)) {
      $data['persona'] = $this->id;
    }
    return $data;
  }

}
