<?php

namespace Ijeffro\Laralocker\LearningLocker\Visualisations;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class VisualisationHandler extends APIHandler {

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

  private $visualisation = '/visualisation';
  private $api = '/api';
  private $v2 = '/v2';

  protected $headers = [
    'content-type' => 'application/json'
  ];


    /**
     * Learning Locker: Request Organisation Details
     *
     * @return  $response
     */
    public function get() {
      try {
        $url = $this->url . $this->api . $this->v2 . $this->visualisation;
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
        $response = $this->patchToLearningLocker($this->url($this->id ?? $this->id), $data);
        
        return $response;
      } catch (Exception $e) {
        return $e;
      }
    }

}
