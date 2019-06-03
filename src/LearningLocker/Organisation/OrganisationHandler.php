<?php

namespace Ijeffro\Laralocker\LearningLocker\Organisation;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class OrganisationHandler extends APIHandler implements OrganisationInterface {

    private $organisation = '/organisation';
    private $api = '/api';
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
     * Learning Locker: Get Organisation Details
     *
     * @return  $response
     */
    public function get() {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->organisation;
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
            $url = $this->url . $this->api . $this->v2 . $this->organisation . '/' . $this->id ?? $this->id;
            $response = $this->save($url, $data);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }

}
