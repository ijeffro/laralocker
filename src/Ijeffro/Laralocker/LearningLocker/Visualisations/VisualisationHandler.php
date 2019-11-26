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
   * Construct the Learning Locker URL
   *
   * @param string|null $id
   * @return string
   */
  public function url(string $id = null) {
    return implode('/', [trim($this->url . $this->api . $this->v2 . $this->visualisation, '/'), $id ?? $id]);
  }

  /**
   * Learning Locker: Get Visualisations
   *
   * @param array $selected
   * @return object $response
   */
  public function get(array $selected = []) {
    try {
      if ($selected) {
        return $this->select($selected,
          $this->getFromLearningLocker($this->url($this->id ?? $this->id))
        );
      }

      return $this->getFromLearningLocker($this->url($this->id ?? $this->id));
    } catch (Exception $e) {
      Log::error('Getting Visualisation: ' . $e);
    }
  }

  /**
   * Learning Locker: Update Visualisation
   *
   * @return  $response
   */
  public function update($data) {
    try {
      return $this->patchToLearningLocker($this->url($this->id ?? $this->id), $data);;
    } catch (Exception $e) {
      Log::error('Updating Visualisation: ' . $e, array('context' => $data));
    }
  }

  /**
   * Learning Locker: Delete Visualisation
   *
   * @return  $response
   */
  public function delete() {
    try {
      return $this->deleteFromLearningLocker($this->url($this->id));
    } catch (Exception $e) {
      Log::error('Deleting Visualisation: ' . $e);
    }
  }

  /**
   * Learning Locker: Create Visualisation
   *
   * @return  $response
   */
  public function create($data = null) {
    try {
      return $this->postToLearningLocker($this->url(), $data);;
    } catch (Exception $e) {
      Log::error('Creating Visualisation: ' . $e, array('context' => $data));
    }
  }
}
