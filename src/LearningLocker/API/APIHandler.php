<?php

namespace Ijeffro\Laralocker\LearningLocker\API;

use Ijeffro\Laralocker\LearningLocker\Connection;

class APIHandler extends Connection {

    /**
     * Learning Locker: Requst URL
     *
     * @return  $response
     */
    public function request($url) {
        try {
            $response = $this->client()->get($url, [
                'auth' => $this->auth(),
                'headers' => $this->headers()
            ]);

            return $response->getBody()->getContents();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function save($url, $data)
    {
        try {
            $data = json_encode($data);
            $response = $this->client()->patch($url, [
                'auth' => $this->auth(),
                'headers' => $this->headers(),
                'body' => $data
            ]);

            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            return $e;
        }
    }

    public function destroy($url)
    {
        try {
            $response = $this->client()->delete($url, [
                'auth' => $this->auth(),
                'headers' => $this->headers()
            ]);

            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            return $e;
        }
    }

    public function make($url, $data = null)
    {
        try {
            $data = json_encode($data);
            $response = $this->client()->post($url, [
                'auth' => $this->auth(),
                'headers' => $this->headers(),
                'body' => $data
            ]);
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            return $e;
        }
    }
        // dd($this->headers());
        // $response = $client->get('http://httpbin.org/get');
        // $response = $client->delete('http://httpbin.org/delete');
        // $response = $client->head('http://httpbin.org/get');
        // $response = $client->options('http://httpbin.org/get');
        // $response = $client->patch('http://httpbin.org/patch');
        // $response = $client->post('http://httpbin.org/post');
        // $response = $client->put('http://httpbin.org/put');

}
