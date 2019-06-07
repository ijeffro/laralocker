<?php

namespace Ijeffro\Laralocker\LearningLocker\Stores;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class StoreHandler extends APIHandler {

    private $store = '/lrs';
    private $api = '/api';
    private $v1 = '/v1';
    private $v2 = '/v2';

    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    public function __construct($id = null) {
        parent::__construct();

        $this->id = $id ? $id : null;
    }

    /**
     * Learning Locker: Request Organisation Details
     *
     * @return  $response
     */
    public function get($selected = []) {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->store . '/' . $this->id ?? $this->id;
            $response = $this->request($url);

            if ($selected) $response = $this->select($selected, $response);

            return $response;

        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Learning Locker: Update Store
     *
     * @return  $response
     */
    public function update($data) {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->store . '/' . $this->id;
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
            $url = $this->url . $this->api . $this->v2 . $this->store . '/' . $this->id;
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
            $url = $this->url . $this->api . $this->v2 . $this->store;
            $response = $this->make($url, $data);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }
}
