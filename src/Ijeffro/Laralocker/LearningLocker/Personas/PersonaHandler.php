<?php

namespace Ijeffro\Laralocker\LearningLocker\Personas;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class PersonaHandler extends APIHandler {

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

  private $persona = '/persona';
  private $api = '/api';
  private $v2 = '/v2';

  protected $headers = [
    'Content-Type' => 'application/json'
  ];

  /**
   * Learning Locker API: Persona Identifier
   *
   * @param string|null $id
   * @return IdentifierHandler
   */
  public function identifier(string $id = null)
  {
    $identifier = new IdentifierHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($identifier) return $identifier;

    return self::identifier($id ? $id : null);
  }

  /**
   * Learning Locker API: Persona Attributes
   *
   * @param string|null $id
   * @return AttributeHandler
   */
  public function attributes(string $id = null)
  {
    $attribute = new AttributeHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($attribute) return $attribute;

    return self::attribute($id ? $id : null);
  }

  /**
   * Learning Locker API: Persona Attributes
   *
   * @param null|integer|string $id
   * @return AttributeHandler
   */
  public function attribute(string $id = null)
  {
    $attribute = new AttributeHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($attribute) return $attribute;

    return self::attribute($id ? $id : null);
  }

  /**
   * Learning Locker API: Persona
   *
   * @param $id
   * @return PersonaHandler
   */
  public function import(string $id = null)
  {
    $import = new ImportHandler($id ? $id : null, $this->key, $this->secret, $this->url);
    if ($import) return $import;

    return self::import($id ? $id : null);
  }


/**
 * Construct the Learning Locker URL
 *
 * @param string|null $id
 * @return string
 */
public function url(string $id = null) {
  return implode('/', [trim($this->url . $this->api . $this->v2 . $this->persona, '/'), $id ?? $id]);
}
  /**
   * Learning Locker: Request Persona Details
   *
   * @return  $response
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
      $response = $this->patchToLearningLocker($this->url($this->id ?? $this->id), $data);
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
      return $e;
    }
  }

}
