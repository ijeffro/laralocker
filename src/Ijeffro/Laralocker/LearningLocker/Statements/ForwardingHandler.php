<?php

namespace Ijeffro\Laralocker\LearningLocker\Statements;

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

  private $statement_forwarding = '/statementforwarding';
  private $api = '/api';
  private $v2 = '/v2';

  protected $headers = [
    'Content-Type' => 'application/json',
  ];

  /**
   * Learning Locker Statement Forwarding Owner
   *
   * @param string || Owner $id
   * @return object
   */
  public function owner(string $id)
  {
    $this->owner = $id;
    return $this;
  }

  /**
   * Learning Locker Statement Forwarding LRS
   *
   * @param string || LRS $id
   * @return object
   */
  public function lrs(string $id)
  {
    $this->lrs_id = $id;
    return $this;
  }

  /**
   * Learning Locker Statement Forwarding Description
   *
   * @param string || Store $id
   * @return object
   */
  public function store(string $id)
  {
    $this->lrs_id = $id;
    return $this;
  }

  /**
   * Learning Locker Statement Forwarding Description
   *
   * @param string || Created At $id
   * @return object
   */
  public function createdAt(string $timesstamp)
  {
    $this->createdAt = $timesstamp;
    return $this;
  }

  /**
   * Learning Locker Statement Forwarding Description
   *
   * @param string || Updated At $id
   * @return object
   */
  public function updatedAt(string $timesstamp)
  {
    $this->updateddAt = $timesstamp;
    return $this;
  }

  /**
   * Learning Locker Statement Forwarding Description
   *
   * @param string || Description $data
   * @return object
   */
  public function description(string $data)
  {
    $this->description = $data;
    return $this;
  }

  /**
   * Learning Locker Statement Forwarding Active
   *
   * @param bool || Active $data
   * @return object
   */
  public function active(bool $data)
  {
    $this->active = $data;
    return $this;
  }

  /**
   * Learning Locker Statement Forwarding isPublic
   *
   * @param bool || isPiblic $data
   * @return object
   */
  public function isPublic(bool $data)
  {
    $this->isPublic = $data;
    return $this;
  }

  /**
   * Learning Locker Statement Forwarding Query
   *
   * @param string || Query $data
   * @return object
   */
  public function query(string $data)
  {
    $this->query = $data;
    return $this;
  }


  /**
   * Learning Locker Statement Forwarding Configuration
   *
   * @param array || Configuration $data
   * @return object
   */
  public function configuration(array $data)
  {
    $this->config = $data;
    return $this;
  }


  /**
   * Learning Locker Statement Forwarding Configuration
   *
   * @param array || Configuration $data
   * @return object
   */
  public function config(array $data)
  {
    $this->config = $data;
    return $this;
  }

  /**
   * Construct the Learning Locker URL
   *
   * @param string|null $id
   * @return string
   */
  public function url(string $id = null) {
    return implode('/', [trim($this->url . $this->api . $this->v2 . $this->statement_forwarding, '/'), $id ?? $id]);
  }

  /**
   * Learning Locker: Get Clients
   *
   * @param array $selected
   * @return object $response
   */
  public function get(array $selected = []) {
      if ($selected) {
        return $this->select($selected, $this->getFromLearningLocker(
            $this->url($this->id ?? $this->id)
          )
        );
      }

    return $this->getFromLearningLocker($this->url($this->id ?? $this->id));;
  }

  /**
   * Learning Locker: Update Client
   *
   * @return  $response
   */
  public function update($data) {
    return $this->patchToLearningLocker($this->url($this->id ?? $this->id), $this->additions($data));
  }

  /**
   * Learning Locker: Delete Client
   *
   * @return  $response
   */
  public function delete() {
    return $this->deleteFromLearningLocker($this->url($this->id));
  }

  /**
   * Learning Locker: Create Client
   *
   * @return  $response
   */
  public function create(array $data = null) {
    return $this->postToLearningLocker($this->url(null), $this->additions($data));
  }

  /**
   * Learning Locker: Update Or Create Statement Forwarding
   *
   * @param array|null $data
   * @return ClientResponse
   */
  public function updateOrCreate(array $data = null) {
    $put_to_learning_locker = $this->putToLearningLocker($this->url($this->id ?? $this->id), $data);
    if (!isset($put_to_learning_locker->_id)) {
      return $this->create($data);
    } else {
      return $put_to_learning_locker;
    }
  }

  public function additions($data) {
    if (isset($this->id)) $data['_id'] = $this->id;
    if (isset($this->config)) $data['configuration'] = $this->config;
    if (isset($this->active)) $data['active'] = $this->active;
    if (isset($this->isPublic)) $data['isPublic'] = $this->isPublic;
    if (isset($this->description)) $data['description'] = $this->description;
    if (isset($this->lrs_id)) $data['lrs_id'] = $this->lrs_id;
    if (isset($this->owner)) $data['owner'] = $this->owner;
    if (isset($this->createdAt)) $data['createdAt'] = $this->createdAt;
    if (isset($this->updatedAt)) $data['updatedAt'] = $this->updatedAt;

    return $data;
  }

}
