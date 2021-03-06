<?php

namespace Ijeffro\Laralocker\LearningLocker\Dashboards;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class DashboardHandler extends APIHandler implements DashboardInterface {


    private $dashboard = '/dashboard';
    private $api = '/api';
    private $v1 = '/v1';
    private $v2 = '/v2';

    protected $headers = [
      'Content-Type' => 'application/json'
    ];

    public function __construct($id = null) {
        parent::__construct();
        $this->id = $id;
    }

    /**
     * Learning Locker API: Get Dashboard
     *
     * @return  $response
     */
    public function get() {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->dashboard;
            $response = $this->request($url);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Learning Locker API: Update Dashboard
     *
     * @param  $data
     * @return $response
     */
    public function update($data) {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->dashboard . '/' . $this->id ?? $this->id;
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
            $url = $this->url . $this->api . $this->v2 . $this->dashboard . '/' . $this->id;
            $response = $this->destroy($url);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Learning Locker API: Create Dashboard
     *
     * @param  $data
     * @return $response
     */
    public function create($data = null) {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->dashboard;
            $response = $this->make($url, $data);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }
}
