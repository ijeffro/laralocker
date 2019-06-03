<?php

namespace Ijeffro\Laralocker\LearningLocker\Personas;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class PersonaHandler extends APIHandler implements PersonaInterface {

    private $persona = '/persona';
    private $api = '/api';
    private $v1 = '/v1';
    private $v2 = '/v2';

    protected $headers = [
      'Content-Type' => 'application/json'
    ];

    function __construct($id = null) {
        parent::__construct();
        $this->id = $id;
    }

    /**
     * Learning Locker: Request Persona Details
     *
     * @return  $response
     */
    public function get() {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->persona;
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
            $url = $this->url . $this->api . $this->v2 . $this->persona . '/' . $this->id ?? $this->id;
            $response = $this->save($url, $data);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }

}
