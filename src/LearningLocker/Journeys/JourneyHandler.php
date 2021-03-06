<?php

namespace Ijeffro\Laralocker\LearningLocker\Journeys;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;
use Ijeffro\Laralocker\LearningLocker\JourneyProgress\JourneyProgressHandler;

class JourneyHandler extends APIHandler {

    private $journey = '/journey';
    private $api = '/api';
    private $v1 = '/v1';
    private $v2 = '/v2';

    public $journey_progress;

    protected $headers = [
      'Content-Type' => 'application/json'
    ];

    function __construct($id = null) {
        parent::__construct();
        $this->id = $id;
    }

    /**
     * Learning Locker API: Clients
     *
     * @return ClientHandler
     */
    public function progress($id = null)
    {
        if ($this->journey_progress) return $this->journey_progress;

        $this->journey_progress = new JourneyProgressHandler($id ? $id : null);
        return $this->progress($id ? $id : null);
    }

    /**
     * Learning Locker: Request Organisation Details
     *
     * @return  $response
     */
    public function get() {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->journey;
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
            $url = $this->url . $this->api . $this->v2 . $this->journey . '/' . $this->id ?? $this->id;
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
            $url = $this->url . $this->api . $this->v2 . $this->journey . '/' . $this->id;
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
            $url = $this->url . $this->api . $this->v2 . $this->journey;
            $response = $this->make($url, $data);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }

}
