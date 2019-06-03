<?php

namespace Ijeffro\Laralocker\Statements;

use Log;
use Carbon\Carbon;

class StatementGenerator {

    const LL_COURSE_EXTENSION  = 'http://learninglocker.net/extensions/course';
    const COMMENT_EXTENSION  = 'http://curatr3.com/define/extension/commentText';
    const BADGE_EXTENSION  = 'http://specification.openbadges.org/xapi/extensions/badgeassertion';
    const MCQ_SINGLE_EXTENSION  = 'http://curatr3.com/define/extension/single-mcq-option';
    const MCQ_MULTIPLE_EXTENSION  = 'http://curatr3.com/define/extension/multiple-mcq-options';
    const CONTRIBUTION_OBJECT  = 'http://curatr3.com/define/extension/contribution-object';
    const BADGE_TYPE = 'http://activitystrea.ms/schema/1.0/badge';
    const CATEGORY_TYPE = 'http://activitystrea.ms/schema/1.0/collection';
    const COLLECTION_TYPE = 'http://curatr3.com/define/type/collection';
    const COMMENT_TYPE = 'http://activitystrea.ms/schema/1.0/comment';
    const COURSE_TYPE = 'http://adlnet.gov/expapi/activities/course';
    const FILE_TYPE = 'http://activitystrea.ms/schema/1.0/file';
    const GATE_TYPE = 'http://curatr3.com/define/type/gate';
    const LEVEL_TYPE = 'http://curatr3.com/define/type/level';
    const OBJECT_TYPE = 'http://activitystrea.ms/schema/1.0/article';
    const ORGANISATION_TYPE = 'http://curatr3.com/define/type/organisation';
    const PAGE_TYPE = 'http://activitystrea.ms/schema/1.0/page';
    const QUESTION_TYPE = 'http://activitystrea.ms/schema/1.0/question';
    const RESPONSE_TYPE = 'http://curatr3.com/define/type/response';
    const SECTION_TYPE = 'http://activitystrea.ms/schema/1.0/collection';
    const TAG_TYPE = 'http://id.tincanapi.com/activitytype/tag';
    const TAG_TYPE_EXTENSION = 'http://curatr3.com/define/extension/tag-type';

    public $url, $statement, $endpoints, $log;

    public function __construct( $endpoints=array() ) {

      $this->log = \App::make('xapi.log');
      $this->endpoints = $endpoints;
    }

    /**
  * Checks a passed array has a defined key, returns a default if not set
  * @param array $array The array to check
  * @param string $key Key to check
  * @param type $default Default to return if key is not set
  * @return mixed
  */
    private static function checkKey( $array, $key, $default='' ){
        return (isset($array[$key]) && !empty($array[$key])) ? $array[$key] : $default;
    }

  /**
  * Creates a statement from an array, with defaults if needed
  * @param Array $data Holds the actor, verb and object information
  */
  public function buildStatement($data) {

    $actor      = $this->checkKey($data, 'actor');
    $verb       = $this->checkKey($data, 'verb',        self::getVerb('experienced') );
    $object     = $this->checkKey($data, 'object');
    $authority  = $this->checkKey($data, 'authority', []);
    $context    = $this->checkKey($data, 'context', [] );
    $result     = $this->checkKey($data, 'result', [] );
    $timestamp  = $this->checkKey($data, 'timestamp',   Carbon::now()->format("c") );

    $this->statement = array(
      'actor'     => $actor,
      'verb'      => $verb,
      'object'    => $object,
      'timestamp' => $timestamp
    );

    if( isset($result) && !empty($result) ){
        $this->statement['result'] = $result;
    }

    if( isset($authority) && !empty($authority) ){
        $this->statement['authority'] = $authority;
    }

    if( isset($context) && !empty($context) ){
        $this->statement['context'] = $context;
    } else {
      $this->statement['context'] = $this->createContext();
    }

    //Add the org to the contextActivites
    $org = \Organisation::userCurrent();
    if( $org ){
      if( !isset($this->statement['context']['contextActivities']) ){
        $this->statement['context']['contextActivities'] = [];
      }

      if( !isset($this->statement['context']['contextActivities']['grouping']) ){
        $this->statement['context']['contextActivities']['grouping'] = [];
      } elseif( !isset($this->statement['context']['contextActivities']['grouping'][0])){
        $val = $this->statement['context']['contextActivities']['grouping'];
        $this->statement['context']['contextActivities']['grouping'] = [ $val ];
      }

      $this->statement['context']['contextActivities']['grouping'][] = $org->tcExtension();
    }

    // remove context.platform if NOT an activity (xAPI 4.1.6)
    if (
        isset($this->statement['context'])
        && isset($this->statement['object'], $this->statement['object']['objectType'])
        && strtolower($this->statement['object']['objectType']) !== strtolower('activity')
        ) {
      if (isset($this->statement['context']['platform'])) {
        unset($this->statement['context']['platform']);
      }
      if (isset($this->statement['context']['revision'])) {
        unset($this->statement['context']['revision']);
      }
    }


    return $this->statement;
  }

  /**
  * Static method
  * Generates the actor part of the statement
  * @param string $name
  * @param string $email
  * @return array
  */
    public static function createMboxActor( $name, $email ){
        return array(
            'name'          =>  $name,
            'mbox'          =>  'mailto:' . $email,
            'objectType'    =>  'Agent'
        );
    }

    /**
     * Static method
     * Generates the actor part of the statement
     * @param string $name
     * @param string $homepage
     * @param string $ref
     * @return array
     */
    public static function createAccountActor( $name, $homepage, $ref ){
      return [
          'name'          => $name,
          'account'       => ['homePage' => $homepage, 'name' => $ref],
          'objectType'    => 'Agent'
      ];
    }

    /**
     * Static method
     * Generates the authority part of the statement
     * @param string $name
     * @param string $email
     * @return array
     */
    public static function createAuthority( $name, $email ){
        return array(
            'name'          =>  $name,
            'mbox'          =>  'mailto:' . $email,
            'objectType'    =>  'Agent'
        );
    }

    /**
     * Static method
     * Generates the result part of the statement
     * @return array
     */
    public static function createResult( $result=array() ){

        return $result;

    }

    /**
     * Static method
     * Generates the context extension part of the statement
     * @todo hardcoded for now
     * @return array
     */
    public static function createContext( $extensions = array(), $additional=null ){

        $context = array(
            'platform'  =>  'Curatr'
        );

        if( !empty($extensions) ){
          $context['extensions'] = $extensions;
        }

        if( !is_null($additional) ){
            $context = array_merge($context, $additional );
        }

        return $context;

    }

  /**
  * Static method
  * Returns a predefined verb or 'experienced' if not found
  * @param string $key The verb to use
  * @return array
  */
    public static function getVerb( $key ){

        $verbs = array();

        $available_verbs = self::returnAvailableVerbs();

        // add all official verbs
        foreach( $available_verbs as $v ){
            $verbs[$v] = array(
                "id"        =>  "http://adlnet.gov/expapi/verbs/{$v}",
                "display"   =>  array( "en-GB" =>  $v ),
            );
        }

        // Standard Verbs
        $verbs['commented'] = array(
            "id"        =>  "http://adlnet.gov/expapi/verbs/commented",
            "display"   =>  array(  "en-GB" =>  "commented on" )
        );
        $verbs['coached'] = array(
            "id"        =>  "http://learninglocker.net/verbs/coached-in",
            "display"   =>  array(  "en-GB" =>  "coached in" )
        );
        $verbs['mentored'] = array(
            "id"        =>  "http://learninglocker.net/verbs/mentored-in",
            "display"   =>  array(  "en-GB" =>  "mentored in" )
        );
        $verbs['observed'] = array(
            "id"        =>  "http://learninglocker.net/verbs/observed-in",
            "display"   =>  array(  "en-GB" =>  "observed in" )
        );
        $verbs['trained'] = array(
            "id"        =>  "http://learninglocker.net/verbs/trained-in",
            "display"   =>  array(  "en-GB" =>  "trained in" )
        );
        $verbs['endorsed'] = array(
            "id"        =>  "http://learninglocker.net/verbs/endorsed-in",
            "display"   =>  array(  "en-GB" =>  "endorsed in" )
        );
        $verbs['posted'] = array(
            "id"        =>  "http://curatr3.com/define/verb/posted",
            "display"   =>  array( "en-GB" =>  "posted" )
        );
        $verbs['published'] = array(
            "id"        =>  "http://curatr3.com/define/verb/published",
            "display"   =>  array( "en-GB" =>  "published" )
        );
        $verbs['read'] = array(
            "id"        =>  "http://curatr3.com/define/verb/read",
            "display"   =>  array( "en-GB" =>  "read" )
        );
        $verbs['liked'] = array(
            "id"        =>  "http://curatr3.com/define/verb/liked",
            "display"   =>  array( "en-GB" =>  "liked" )
        );
        $verbs['bookmarked'] = array(
            "id"        =>  "http://curatr3.com/define/verb/bookmarked",
            "display"   =>  array( "en-GB" =>  "bookmarked" )
        );
        $verbs['earned'] = array(
            "id"        =>  "http://curatr3.com/define/verb/earned",
            "display"   =>  array( "en-GB" =>  "earned" )
        );
        $verbs['badge_earned'] = array(
            "id"        =>  "http://specification.openbadges.org/xapi/verbs/earned",
            "display"   =>  array( "en-GB" =>  "earned" )
        );
        $verbs['login'] = array(
            "id"        =>  "https://brindlewaye.com/xAPITerms/verbs/loggedin/",
            "display"   =>  array(  "en-GB" =>  "logged in to" )
        );
        $verbs['logout'] = array(
            "id"        =>  "http://curatr3.com/define/verb/logout",
            "display"   =>  array(  "en-GB" =>  "logged out of" )
        );
        $verbs['edited'] = array(
            "id"        =>  "http://curatr3.com/define/verb/edited",
            "display"   =>  array( "en-GB" =>  "edited" )
        );
        $verbs['uploaded'] = array(
            "id"        =>  "http://curatr3.com/define/verb/uploaded",
            "display"   =>  array( "en-GB" =>  "uploaded" )
        );
        $verbs['accessed'] = array(
            "id"        =>  "http://activitystrea.ms/schema/1.0/access",
            "display"   =>  array( "en-GB" =>  "accessed" )
        );
        $verbs['voted-up'] = array(
            "id"        =>  "http://curatr3.com/define/verb/voted-up",
            "display"   =>  array(  "en-GB" =>  "voted up" )
        );
        $verbs['voted-down'] = array(
            "id"        =>  "http://curatr3.com/define/verb/voted-down",
            "display"   =>  array(  "en-GB" =>  "voted down" )
        );
        $verbs['flag-as-inappropriate'] = array(
            "id"        =>  "http://activitystrea.ms/schema/1.0/flag-as-inappropriate",
            "display"   =>  array( "en-GB" =>  "flagged as inappropriate" )
        );
        $verbs['join'] = array(
            "id"        =>  "http://activitystrea.ms/schema/1.0/join",
            "display"   =>  array( "en-GB" =>  "joined" )
        );
        $verbs['leave'] = array(
            "id"        =>  "http://activitystrea.ms/schema/1.0/leave",
            "display"   =>  array( "en-GB" =>  "left" )
        );
        $verbs['opened'] = array(
            "id"        =>  "http://activitystrea.ms/schema/1.0/open",
            "display"   =>  array( "en-GB" =>  "opened" )
        );

        return self::checkKey( $verbs, $key, $verbs['experienced']);
    }

    /**
     * Create the object section of statement
     *
     * @param int $id
     * @param string $name
     * @param string $description
     * @param array $extensions
     * @return array
     *
     */
    public static function createObject( $id, $name=null, $description=null, $type=null, $extensions = array() )
    {
        $object = array(
        'objectType'    => 'Activity',
        'id'            => $id
        );

        $definition = array();

        if (!empty($name)) {
        $definition['name'] = [
            'en-GB' => $name
        ];
        }

        if( !empty($description)){
            $definition['description'] = [
            'en-GB'=>$description
        ];
        }

        if( !empty($type)){
        $definition['type'] = $type;
        }

        if( !empty($extensions) ){
        $definition['extensions'] = $extensions;
        }

        if (!empty($definition)) {
        $object['definition'] = $definition;
        }

        return $object;
    }



  public function sendRequests() {
    foreach( $this->endpoints as $name => $ep ){

      $username = $ep->username;
      $password = $ep->key;
      $url = $ep->url;

      if(substr($url, -1) !== '/') {
        $url .= '/';
      }

      \Queue::push('\HT2\Queues\XAPIService', [
        'endpoint'  => $url,
        'type'      => 'basic',
        'username'  => $username,
        'password'  => $password,
        'statement' => $this->statement
      ]);

    }
  }

  /**
  * Send the request to all end points defined in the endpoints array
  * @return array An array of all requests sent and their responses
  */
    public function sendRequestsOld(){
      $error = $this->checkError();
      if( $error ) return false;

      $results = array();


      foreach( $this->endpoints as $name => $ep ){

          $username = $ep->username;
          $password = $ep->key;
          $url = $ep->url;
          $auth = base64_encode( $username.':'.$password );

          $result = $this->make_request($auth, $url, $this->statement);
          $error = false;
          $error_type = "";
          $body = "";

          if( isset($result['error']) ){
            $error = true;
            $error_type = "cURL error";
          } else {

            $body = isset($result['body']) ? json_decode($result['body']) : "";

            if( isset($body->success) ){
              if( $body->success == false ){
                $error = true;
                $error_type = "LRS returned success as false";
              }
            }

            if( isset($body->error) ){
              if( $body->error == true ){
                $error = true;
                $error_type = "LRS returned error as true";
              }
            }
            if( isset($result['status']) ){
              $status = $result['status'];

              switch( intval($status) ){
                case 400:
                case 404:
                case 500:
                  $error = true;
                  $error_type = "LRS returned HTTP code ".$status;
                break;
              }
            }
          }

          if( $error ){
            $this->log->error( 'Statement Failed', array(
              'curl_result' =>  $result,
              'type'        =>  $error_type,
              'url'         =>  $ep->url,
              'statement'   =>  $this->statement
            ));
          } else {
            $this->log->info( 'Sent', array(
              'url'         =>  $ep->url,
              'statement'   =>  $this->statement
            ));
          }

          $results[$url] = $body;
      }


      return array(
          'statement' =>  $this->statement,
          'endpoints' =>  $this->endpoints,
          'results'   =>  $results
      );
  }

  /**
  * Send statement
  * @param string $auth The base64 basic auth info for the request
  * @param string $url The endpoint
  * @param array $statement The statement to be encoded
  * @return boolean/array Returns false on fail, array of details on success
  */
  public function make_request( $auth, $url, $statement ) {

    $headers =   array(
      'Authorization: Basic '.$auth,
      'Content-Type: application/json; charset=UTF-8',
      'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'X-Experience-API-Version: 1.0.0'
    );

    if(substr($url, -1) !== '/') {
      $url .= '/';
    }

    $ch = curl_init( $url . 'statements' );
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($statement) );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 1);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );

    if( substr($url, 0, 5) == "https" ){
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt ($ch, CURLOPT_CAINFO, storage_path()."/cacert.pem");
    }

    $response = curl_exec( $ch );
    if( $response === false ){
      return array('error'=>true, 'curl_error'=>curl_error($ch));
    } else {
      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $header = substr($response, 0, $header_size);
      $body = substr($response, $header_size);
      return array(
        'ch'      =>  $ch,
        'status'  =>  curl_getinfo($ch, CURLINFO_HTTP_CODE),
        'header'  =>  $header,
        'body'    =>  $body
      );
    }
  }

    private function checkError(){
        if( empty($this->statement) ){
            $this->error("No data set");
            return true;
        }

        if( !isset($this->statement['actor']) ){
            $this->error("No actor set");
            return true;
        }

        if( !isset($this->statement['verb']) ){
            $this->error("No verb set");
            return true;
        }

        if( !isset($this->statement['object']) ){
            $this->error("No object set");
            return true;
        }

        return false;
    }

    private function error( $message ){
        $this->log->error( "Statement Generator Error: ".$message );
    }

    /**
     * We will probably move this to the db, or hook into an existing service?
     *
     **/
    private static function returnAvailableVerbs(){
        return array(
            'answered',
            'asked',
            'attempted',
            'attended',
            'commented',
            'completed',
            'exited',
            'experienced',
            'failed',
            'imported',
            'initialized',
            'interacted',
            'launched',
            'mastered',
            'passed',
            'preferred',
            'progressed',
            'registered',
            'responded',
            'resumed',
            'scored',
            'shared',
            'suspended',
            'terminated',
            'voided');
    }

    public static function makeEndpoint( $url, $username, $password ){
        $ep = new \stdClass;
        $ep->url = $url;
        $ep->username = $username;
        $ep->key = $password;
        $ep->basicAuth = base64_encode(sprintf('%s:%s', $username, $password));
        return $ep;
    }
}
