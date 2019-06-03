<?php
namespace Ijeffro\Laralocker\xAPI;

use TinCan\Agent;
use TinCan\Verb;
use TinCan\Activity;
use TinCan\Statement;
use TinCan\LanguageMap;
use TinCan\Context;
use TinCan\Result;
use Carbon\Carbon;
use Config;

class xAPIHandler implements xAPIInterface {

  /**
   * Holds arrays of cred details, with keys enpoint, username, password
   * @var array
   */
  protected $auth_credentials;

  protected $statement;
  protected $timestamp;
  protected $version = "1.0.1";
  protected $valid_env = ['dev', 'techbac-staging', 'production', 'andrew', 'james', 'anna'];

  public function __construct( Statement $statement = null, Carbon $timestamp = null ) {
    $this->statement = $statement ? $statement : new Statement;
    $this->timestamp = $timestamp ?: new Carbon;
  }

  /**
   * Retrieves the auth credentials
   * If they have not previously been set then grab them
   * @return array
   */
  public function addCredential($endpoint, $username, $password) {
    return $this->auth_credentials[] = [
      'type'     => $type,
      'endpoint' => $endpoint,
      'username' => $username,
      'password' => $password
    ];
  }

  /**
   * Retrieves the auth credentials
   * If they have not previously been set then grab them
   * @return array
   */
  public function getAuthCredentials() {
    return $this->auth_credentials;
  }

  /**
   * Constructs the statements and returns a string format, friendly for our push method
   * @param  Agent    $agent
   * @param  Verb     $verb
   * @param  Activity $activity
   * @param  Carbon   $timestamp
   * @return string   JSON string representation of the statement
   */
  public function makeStatement( Agent $agent, Verb $verb, $object, Carbon $timestamp, Context $context=null, Result $result=null ) {
    $this->statement->setActor( $agent );
    $this->statement->setVerb( $verb );
    $this->statement->setObject( $object );
    $this->statement->setTimestamp( $timestamp );

    if( !is_null($context) ){
      $this->statement->setContext( $context );
    }

    if( !is_null($result) ){
      $this->statement->setResult( $result );
    }

    return $this->statement->asVersion($this->version);
  }

  /**
   * Pass the statement and endpoint details to the queue
   * @param  Agent    $agent
   * @param  Verb     $verb
   * @param  Activity $activity
   * @param  Carbon   $timestamp
   * @return null
   */
  public function send( Agent $agent, $verb, $object, Carbon $timestamp = null, Context $context = null, Result $result = null ) {

    // Stop XAPI send if not in environment that has credentials
    // if( !in_array( Config::get('app.app_env'), $this->valid_env ) ){
    //   return false;
    // }
    if( is_string($verb) ){
      $verb = $this->getVerb($verb);
    }

    //Set a timestamp if not passed
    $timestamp = $timestamp ?: new Carbon;
    //Get JSON version of statement for queue
    $statement = $this->makeStatement( $agent, $verb, $object, $timestamp );

    //Get the credentials
    $auth_credentials = $this->getAuthCredentials();

    //Push a new job onto the queue for each endpoint
    foreach( $auth_credentials as $creds ){
      \Queue::push('\Ijeffro\Laralocker\xAPIService', [
        'endpoint'  => $creds['endpoint'],
        'type'      => $creds['type'],
        'username'  => $creds['username'],
        'password'  => $creds['password'],
        'statement' => $statement
      ]);
    }
  }

  /**
   * Create the verb from an ID
   * If a display is present then construct from that, otherwise check our prescribed list
   * @param  IRI $id      The ID of the verb as defined by the XAPI standard
   * @param  TinCan\LanguageMap $display
   * @return TinCan\Verb
   */
  public function getVerb( $id, $display=null ) {
    $verb = new Verb(['id'=>$id]);

    if( !is_null($display) ){
      $verb->setDisplay($display);
    } else {
      switch( $id ){
        case "https://brindlewaye.com/xAPITerms/verbs/loggedin":
          $verb->setDisplay( $this->makeLanguageMap('logged in to') );
          break;
        case xapi_route('xapi.verb', 'logout'):
          $verb->setDisplay( $this->makeLanguageMap('logged out of') );
          break;
        case "http://activitystrea.ms/schema/1.0/access":
          $verb->setDisplay( $this->makeLanguageMap('accessed') );
          break;
        case "http://activitystrea.ms/schema/1.0/play":
          $verb->setDisplay( $this->makeLanguageMap('played') );
          break;
        case "http://activitystrea.ms/schema/1.0/watch":
          $verb->setDisplay( $this->makeLanguageMap('watched') );
          break;
        case "http://adlnet.gov/expapi/verbs/completed":
          $verb->setDisplay( $this->makeLanguageMap('completed') );
          break;
        case "http://adlnet.gov/expapi/verbs/commented":
          $verb->setDisplay( $this->makeLanguageMap('commented') );
          break;
        case "http://adlnet.gov/expapi/verbs/answered":
          $verb->setDisplay( $this->makeLanguageMap('answered') );
          break;
        case 'http://techbac.com/definitions/xapi/verbs/liked':
          $verb->setDisplay( $this->makeLanguageMap('liked') );
          break;
        case xapi_route('xapi.verb', 'recommended'):
          $verb->setDisplay( $this->makeLanguageMap('recommended') );
          break;
      }
    }

    return $verb;
  }

  /**
   * Get endorse data from Techbac LRS
   *
   * @param $user
   *
   * @return $output
   *
   **/
  public function getEndorsements( $user ){

    if( !$user || $user == NULL ){
      return false;
    }
    $auth_credentials = $this->getAuthCredentials();

    $data = [];

    $xapi_account = $user->xapi_account;

    //Push a new job onto the queue for each endpoint
    foreach( $auth_credentials as $creds ){
      $getData = json_encode(
        [ 'statement.object.account.name' => $xapi_account->getName(),
          'statement.object.account.homePage' => $xapi_account->getHomePage()
        ]
      );

      $url = $creds['reporting_endpoint'].'/api/v1/endorsements?filter=' . $getData;

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_USERPWD, "1f5ffde73fe6e2ba7719d3299fb7448bf625c2ed:960923f9b270f6fab18116f6801ff79dabeb7ca8");
      curl_setopt($ch, CURLOPT_URL, $url);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $result = json_decode( curl_exec($ch), true );
      //dd($result['data']);
      $data = array_merge($data, $result['data']);
      curl_close($ch);
    }


    return $data;
  }

  /**
   * Create a language map with a default set of languages
   * @param  string $value The value to insert in the map
   * @param  array $langs An array of languages to include the value against
   * @return TinCan\LanguageMap
   */
  public function makeLanguageMap( $value, $langs=null ) {
    $langs = $langs ?: ['en-GB', 'en-US'];

    $map_data = [];
    foreach( $langs as $lang ){
      $map_data[$lang] = $value;
    }
    return new LanguageMap($map_data);
  }

  public function getVersion(){ return $this->version; }
  public function setVersion($version){ $this->version = $version; }

}
