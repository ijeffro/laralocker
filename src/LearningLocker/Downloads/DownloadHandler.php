<?php

namespace Ijeffro\Laralocker\LearningLocker\Downloads;

use Ijeffro\Laralocker\LearningLocker\API\APIHandler;

class DownloadHandler extends APIHandler implements DownloadInterface {

    private $download = '/download';
    private $api = '/api';
    private $v1 = '/v1';
    private $v2 = '/v2';

    protected $headers = [
      'content-type' => 'application/json'
    ];

    /**
     * Learning Locker: Request Organisation Details
     *
     * @return  $response
     */
    public function get() {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->download;
            $response = $this->request($url);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }

}
