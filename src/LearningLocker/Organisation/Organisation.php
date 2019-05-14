<?php
namespace HT2\LaraLocker\LearningLocker;

use Exception;

class Organisation extends Connection {

  private $organisation = '/organisation';
  private $api = '/api';
  private $v2 = '/v2';

  private $headers = [
    'content-type' => 'application/json'
  ];

  /**
   * Get the Learning Locker Statement Forward by ID.
   *
   * @param   $id
   * @return  $response
   */
  public function get() {
    try {
      $url = $this->endpoint . $this->api . $this->v2 . $this->organisation;
      $request = $this->getClient()->get($url, [
        'auth' => $this->getAuth(),
        'headers' => [
          'content-type' => 'application/json'
        ],
      ]);
      $response = $request->json();
      return $response;
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * Get the Learning Locker Organisation Name.
   *
   * @param   $org
   * @return  $response, false
   */
  public function id() {
    $response = $this->get();
    if($response) {
      $id = $response[0]['_id'];
      $setSetting = LLService::create($this->org, LLService::LL_ID)->setSetting(LLService::ORGANISATION_ID, $id);
      return $id;
    }
    return false;
  }

  /**
   * Get the Learning Locker Organisation Name.
   *
   * @param   $org
   * @return  $response, false
   */
  public function name()
  {
    $response = $this->get();
    if($response) {
      $name = $response[0]['name'];
      $setSetting = LLService::create($this->org, LLService::LL_ID)->setSetting(LLService::ORGANISATION, $name);
      return $name;
    }
    return false;

  }

}
