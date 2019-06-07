<?php

namespace Ijeffro\Laralocker\LearningLocker\Statements;


use Ijeffro\Laralocker\LearningLocker\API\APIHandler;
use Ijeffro\Laralocker\LearningLocker\StatementForwarding\StatementForwardingHandler;

class StatementHandler extends APIHandler {

    private $statements = '/statement';
    private $api = '/api';
    private $v1 = '/v1';
    private $v2 = '/v2';

    public $statement_forwarding;

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
    public function forwarding($id = null)
    {
        if ($this->statement_forwarding) return $this->statement_forwarding;

        $this->statement_forwarding = new StatementForwardingHandler($id ? $id : null);
        return $this->forwarding($id ? $id : null);
    }

    /**
     * Learning Locker: Request Organisation Details
     *
     * @return  $response
     */
    public function get() {
        try {
            $url = $this->url . $this->api . $this->v2 . $this->statements . '/' . $this->id ?? $this->id;
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
            $url = $this->url . $this->api . $this->v2 . $this->statements . '/' . $this->id ?? $this->id;
            $response = $this->save($url, $data);
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }
}
