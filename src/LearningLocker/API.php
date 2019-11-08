<?php
namespace HT2\Integrations\LearningLocker;

use HT2\Integrations\LearningLocker\Check;
use HT2\Integrations\LearningLocker\Client;
use HT2\Integrations\LearningLocker\Connector;
use HT2\Integrations\LearningLocker\Connection;
use HT2\Integrations\LearningLocker\Organisation;
use HT2\Integrations\LearningLocker\StatementForward;
use HT2\Integrations\LearningLocker\Query;
use Exception;

class API extends Connection {
  protected $org;
  protected $organisationApi;
  protected $clientApi;
  protected $statementForwardApi;
  protected $queryApi;


  /**
   * Organisations Facade
   *
   * @param null
   * @return Organisation
   */
  public function organisation() {
    if ($this->organisationApi) {
      return $this->organisationApi;
    }
    $this->organisationApi = new Organisation($this->org, $this->settings);
    return $this->organisation();
  }

  public function client()
  {
    if ($this->clientApi) {
      return $this->clientApi;
    }
    $this->clientApi = new Client($this->org, $this->settings);
    return $this->client();
  }

  public function statementForward()
  {
    if ($this->statementForwardApi) {
      return $this->statementForwardApi;
    }
    $this->statementForwardApi = new StatementForward($this->org, $this->settings);
    return $this->statementForward();
  }

  public function query()
  {
    if ($this->queryApi) {
      return $this->queryApi;
    }
    $this->queryApi = new Query($this->org, $this->settings);
    return $this->query();
  }

  public function isDomainAvailable($domain) {
    try {
      $url = trim($domain, '/') . '/api';
      $request = $this->getClient()->get($url, ['timeout' => 1]);
      if(!$request) {
        return false;
      }
      if($request->getStatusCode() === 200) {
        return true;
      }
    } catch (Exception $e) {
      return false;
    }
    return false;
  }

}
