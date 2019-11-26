<?php

namespace Ijeffro\Laralocker\LearningLocker\API;

use Config;

class APIHandler extends Connection {

  /**
   * Learning Locker Conection Details
   *
   * @param string $key
   * @param string $secret
   * @param string $url
   *
   * @return void
   */
  public function __construct($key = null, $secret = null, $url = null) {
    $this->key = $key ? $key : null;
    $this->secret = $secret ? $secret : null;
    $this->url = $url ? $url : null;

    parent::__construct($this->key, $this->secret, $this->url);
  }

  const AUTH = 'auth';
  const BODY = 'body';
  const HEADERS = 'headers';



  /**
   * Prepare Request Data
   *
   * @param string $data
   * @return object
   */
  public function data($data) {
    return json_encode($data);
  }

  /**
   * Prepare Request Response
   *
   * @param object $response
   * @return array
   */
  public function response($response) {
    return json_decode($response->getBody()->getContents());
  }


  /**
   * Learning Locker: Requst URL
   *
   * @return  $response
   */
  public function getFromLearningLocker($url) {
    try {
      $response = $this->client()->get($url, [
        self::AUTH => $this->auth(),
        self::HEADERS => $this->headers()
      ]);

      if ( $response->getStatusCode() === 404 ) return null;

      return $this->response($response);
    } catch (\Exception $e) {
      if ($e instanceof \GuzzleHttp\Exception\ClientException) {
        return null;
      }
    }
  }

  public function patchToLearningLocker($url, $data)
  {
    try {
      $response = $this->client()->patch($url, [
        self::AUTH => $this->auth(),
        self::HEADERS => $this->headers(),
        self::BODY => is_object($data) ? $data : $this->data($data),
      ]);

      return $this->response($response);
    } catch (\Exception $e) {
      if ($e instanceof \GuzzleHttp\Exception\ClientException) {
        return null;
      }
    }
  }

  public function putToLearningLocker($url, $data)
  {
    try {
      $response = $this->client()->put($url, [
        self::AUTH => $this->auth(),
        self::HEADERS => $this->headers(),
        self::BODY => is_object($data) ? $data : $this->data($data)
      ]);

      return $this->response($response);
    } catch (\Exception $e) {
      if ($e instanceof \GuzzleHttp\Exception\ClientException) {
        return null;
      }
    }
  }

  public function deleteFromLearningLocker(string $url) {
    try {
      $response = $this->client()->delete($url, [
          self::AUTH => $this->auth(),
          self::HEADERS => $this->headers()
      ]);

      if ($response->getStatusCode() === 404) return null;

      return $this->response($response);

    } catch (\Exception $e) {
      if ($e instanceof \GuzzleHttp\Exception\ClientException) {
        return $this->postToLearningLocker($url);
      }
    }
  }

  /**
   * Learning Locker Post
   *
   * @param string $url
   * @param string|null $data
   * @return Client
   */
  public function postToLearningLocker(string $url, $data = null) {
    try {
      $response = $this->client()->post($url, [
        self::AUTH => $this->auth(),
        self::HEADERS => $this->headers(),
        self::BODY => is_object($data) ? $data : $this->data($data)
      ]);

      return $this->response($response);
    } catch (\Exception $e) {
      if ($e instanceof \GuzzleHttp\Exception\ClientException) {
        return $this->postToLearningLocker($url);
      }
    }
  }

  public function select(array $selected = [], $response) {
    if ($selected) return $this->getSelected($selected, $response);
    return $response;
  }


  public function getSelected(array $selected = [], $response) {
    $items = [];
    $response = $this->response($response);

    foreach ($selected as $select) {
     $search = array_key_exists($select, $response);
      if ($search === true) {
        $items[$select] = $response[$select];
      }

    }

    return $this->data($items);
  }



  public function testLearningLocker(string $key = null, string $secret = null, string $url = null)
  {
    try {

      if (Config::has("laralocker.learning_locker.api.url") && !$url && !$this->url) {
        $url = Config::get("laralocker.learning_locker.api.url");
      }


      if ($this->url) {
        $url = $this->url;
      }

      $url = $this->url . '/api';
      $response = $this->client()->get($url, [
        self::AUTH => $this->auth(),
        self::HEADERS => $this->headers()
      ]);

      if ($response->getStatusCode() === 200) {
        return true;
      }

      return $response->getBody()->getContents();
    } catch (ClientException $e) {
      Log::error($e);
    }
  }

  // dd($this->headers());
  // $response = $client->get('http://httpbin.org/get');
  // $response = $client->delete('http://httpbin.org/delete');
  // $response = $client->head('http://httpbin.org/get');
  // $response = $client->options('http://httpbin.org/get');
  // $response = $client->patch('http://httpbin.org/patch');
  // $reponse = $client->post('http://httpbin.org/post');
  // $response = $client->put('http://httpbin.org/put');

}
