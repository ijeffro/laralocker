<?php

namespace Ijeffro\Laralocker\LearningLocker\Organisations;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class OrganisationHandler extends APIHandler {

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

  private $organisation = '/organisation';
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
    return implode('/', [trim($this->url . $this->api . $this->v2 . $this->organisation, '/'), $id ?? $id]);
  }

  /**
   * Learning Locker: Organisation Details
   *
   * @return  $response
   */
  public function get() {
    try {
      $url = $this->url . $this->api . $this->v2 . $this->organisation;
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
            $url = $this->url . $this->api . $this->v2 . $this->organisation . '/' . $this->id ?? $this->id;
            $response = $this->patchToLearningLocker($url, $data);
            return $response;
        } catch (Exception $e) {
            return $e;
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
