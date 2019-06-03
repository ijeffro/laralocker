<?php

namespace Ijeffro\Laralocker\LearningLocker\Roles;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class RoleHandler extends APIHandler implements RoleInterface {

    private $role = '/role';
    private $api = '/api';
    private $v1 = '/v1';
    private $v2 = '/v2';

    protected $headers = [
      'content-type' => 'application/json'
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
            $url = $this->url . $this->api . $this->v2 . $this->role . '/' . $this->id ??  $this->id;
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
            $url = $this->url . $this->api . $this->v2 . $this->role . '/' . $this->id ?? $this->id;
            $response = $this->save($url, $data);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }
}
