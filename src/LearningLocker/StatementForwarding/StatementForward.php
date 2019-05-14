<?php
namespace HT2\Integrations\LearningLocker;

use Guzzle\Exception\RequestException;
use Curatr\Services\LearningLocker as LLService;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use \Exception;

class StatementForward extends Connection {

  private $statementForward = '/statementforwarding';
  private $api = '/api';
  private $v2 = '/v2';

  private $headers = [
    'content-type' => 'application/json'
  ];

  /**
   * Generate the Query for Learning Locker's
   * Statement Forward.
   *
   * @return  true
   * @return  false
   */
  public function updateStatementForwardQuery($statementForwardId, $isEnabled) {
    $query = Query::statementForward($this->org->id);
    if ($query === false || !$isEnabled) {
      $message = $query === false && $isEnabled ? '(No objects)' : null;
      return $this->disable($statementForwardId, $message);
    }
    // pass in the query to prevent regeneration in the enable method
    return $this->enable($statementForwardId, $query);
  }

  public static function getSigner() {
    return new Sha256;
  }

  public static function getOrgIssuer($org) {
    return \Curatr\Helper::getSubdomainURL($org->alias);
  }

  public static function getOrgSigningKey($org) {
    return $org->jwt_secret ?? 'default_key';
  }

  public function createSignedBearer() {
    $signer = self::getSigner();
    $issuer = self::getOrgIssuer($this->org);
    $token = (new Builder())->setIssuer($issuer) // Configures the issuer (iss claim)
                        ->setId($this->org->id, true) // Configures the id (jti claim), replicating as a header item
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->sign($signer, self::getOrgSigningKey($this->org))
                        ->getToken(); // Retrieves the generated token
    return $token->__toString();
  }

  private function title($message = "") {
    return trim($this->org->app_name . ' - ' . $this->org->name . ' - Object Completions ' . $message);
  }

  /**
   * Enable the Learning Locker Statement Forward.
   *
   * @param   $statementForwardId
   * @return  $response
   */
  public function enable($statementForwardId, $query = null) {
    $data = [
      "active" => true,
      "description" => $this->title(),
      "query" => $query ?: Query::statementForward($this->org->id),
      'configuration' => [
        'authType' => 'token',
        'secret' => $this->createSignedBearer()
      ]
    ];
    return $this->update($statementForwardId, $data);
  }

  /**
   * Disable the Learning Locker Statement Forward.
   *
   * @param   $id
   * @return  $response
   */
  public function disable($statementForwardId, $message) {
    $message = $message ?? '(Service Disabled)';
    $data = array(
      "active" => false,
      "description" => $this->title($message),
      "query" => json_encode(['$comment' => Query::COMMENT, 'disabled' => $message])
    );
    return $response = $this->update($statementForwardId, $data);
  }

  /**
   * Get the Learning Locker Statement Forward by ID.
   *
   * @param   $id
   * @return  $response
   */
  public function check($statementForwardId) {
    if (empty($statementForwardId)) return false;
    return $this->get($statementForwardId);
  }

  /**
   * Get the Learning Locker Statement Forward by ID.
   *
   * @param   $statementForwardId
   * @return  $response
   */
  public function get($statementForwardId) {
    try {
      $url = $this->endpoint . $this->api . $this->v2 . $this->statementForward . '/' . $statementForwardId;
      $request = $this->getClient()->get($url, [
        'auth' => $this->getAuth(),
        'headers' => [
          'content-type' => 'application/json'
        ],
      ]);

      if($request->getStatusCode() === 404) {
        throw new GuzzleHttp\Exception\ClientException('There was a issue connecting to Learning Locker.');
      }
      return $request->json();
    } catch (Exception $e) {      
      if ($e instanceof \GuzzleHttp\Exception\ClientException) {
        $responseBody = $e->getResponse()->getBody(true);
        \Log::error('Error fetching statement forward', [
          'id' => $statementForwardId,
          'org_id'=>$this->org->id,
          'responseBody' => $responseBody
        ]);
      }
      return false;
    }
  }

  /**
   * Store the Learning Locker Statement Forward.
   *
   * @param   $data
   * @return  $response
   */ 
  public function store($data, $startEnabled = true) {
    $store = json_encode($data);
    try {
      $url = $this->endpoint . $this->api . $this->v2 . $this->statementForward;
      $request = $this->getClient()->post($url, array(
        'auth' => $this->getAuth(),
        'headers' => $this->headers,
        'body' => $store,
      ));
      $request = $request->json();
      if (isset($request['_id'])) {
        return $this->updateStatementForwardQuery($request['_id'], $startEnabled);
      }
      return false;
    } catch (Exception $e) {      
      if ($e instanceof \GuzzleHttp\Exception\ClientException) {
        $responseBody = $e->getResponse()->getBody(true);
        \Log::error('Error creating statement forward', [
          'data' => $data,
          'org_id'=>$this->org->id,
          'responseBody' => $responseBody
        ]);
      }
      return false;
    }
  }

  /**
   * Update the Learning Locker Statement Forward.
   *
   * @param   $statementForwardId, $data
   * @return  $response
   */
  protected function update($statementForwardId, $data) {
    if (empty($statementForwardId)) return false;
    $update = json_encode($data);
    try {
      $url = $this->endpoint . $this->api . $this->v2 . $this->statementForward . '/' . $statementForwardId;
      $request = $this->getClient()->patch($url, array(
        'auth' => $this->getAuth(), 
        'headers' => [
          'content-type' => 'application/json'
        ],
        'body' => $update,
      ));
      return $request->json();
    } catch (Exception $e) {      
      if ($e instanceof \GuzzleHttp\Exception\ClientException) {
        $responseBody = $e->getResponse()->getBody(true);
        \Log::error('Error updating statement forward', [
          'id' => $statementForwardId,
          'data' => $data,
          'org_id'=>$this->org->id,
          'responseBody' => $responseBody
        ]);
      }
      return false;
    }
  }

}
