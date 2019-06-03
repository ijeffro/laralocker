<?php
namespace LaraLocker\Queues;
/**
* Used to delegate sending xAPI statements to a queue handler
*/
class xAPIService
{
  /**
   * The LRS object
   * @var \TinCan\RemoteLRS
   */
  protected $lrs;

  /**
   * The Statement object
   * @var \TinCan\Statement
   */
  protected $statement;


  function __construct( \TinCan\RemoteLRS $lrs, \TinCan\Statement $statement ) {
    $this->lrs = $lrs ?: new \TinCan\RemoteLRS;
    $this->statement = $statement ?: new \TinCan\Statement;
  }

  /**
   * Fires the job
   * Expect an endpoint, username, password and statement as passed data
   */
  public function fire($job, $data ) {

    if( $job->attempts() > 10 ){ //remove job after 10 tries
      return $job->delete();
    }

    if( !isset($data['endpoint']) || !isset($data['username']) || !isset($data['password']) || !isset($data['statement']) ){
      \Log::error('Insufficient data passed to xapi statement job', $data);
      return $job->delete();
    }

    //Setup the endpoint and version
    $this->lrs->setEndpoint( $data['endpoint'] );
    $this->lrs->setVersion( "1.0.1" );

    //Set the auth details
    switch( $data['type'] ){
      default:
      case "basic":
        $this->lrs->setAuth( $data['username'], $data['password']);
      break;
    }

    $error_msg = 'XAPI error - failed sending statement';

    try {
      //Send the statement
      //This will attempt to push the statement a maximum of 3 times before deleting the job and creating an error
      $response = $this->lrs->saveStatement($data['statement']);
      if( $response->success ){
        //remove the job if succesfull
        return $job->delete();
      } else {
        $content = json_decode($response->content, true);
        if( isset($content['message']) && isset($content['message'][0]) ){
          $error_msg .= " - ".$content['message'][0];
        }
        \Log::error($error_msg, [
          'content'   => $content,
          'data'      => $data
        ]);
        return $job->release(10);
      }
    } catch (\Exception $e) {
      $error_msg .= " - ".$e->getMessage();
      \Log::error($error_msg, [
        'exception' => $e,
        'data' => $data
      ]);
      return $job->release(10);
    }

  }


}
