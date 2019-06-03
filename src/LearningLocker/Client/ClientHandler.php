<?php

namespace Ijeffro\Laralocker\LearningLocker\Client;


use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class ClientHandler extends APIHandler {

    private $client = '/client';
    private $api = '/api';
    private $v1 = '/v1';
    private $v2 = '/v2';

    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];


    function __construct($id = null) {
        parent::__construct();
        $this->id = $id;
    }

    /**
     * Learning Locker: Request Organisation Details
     *
     * @return  $response
     */
    public function get() {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->client . '/' . $this->id ??  $this->id;
            $response = $this->request($url);
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
            $url = $this->url . $this->api . $this->v2 . $this->client . '/' . $this->id ?? $this->id;
            $response = $this->save($url, $data);
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
            $url = $this->url . $this->api . $this->v2 . $this->client . '/' . $this->id;
            $response = $this->destroy($url);
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
            $url = $this->url . $this->api . $this->v2 . $this->client;
            $response = $this->make($url, $data);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }

}
