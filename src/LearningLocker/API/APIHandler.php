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
            if ( $response->getStatusCode() === 404 )
                throw new ClientException(404);

            return $response->getBody()->getContents();
        } catch (ClientException $e) {
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
            if ( $response->getStatusCode() === 404 )
                throw new ClientException(404);

            return $response->getBody()->getContents();
        } catch (ClientException $e) {
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
            if ( $response->getStatusCode() === 404 )
                throw new ClientException(404);

            return $response->getBody()->getContents();
        } catch (ClientException $e) {
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
        } catch (ClientException $e) {
            return $e;
        }
    }

    public function select($selected = [], $response)
    {
        if (!is_array($selected) || !$response)  {
            $error = [ "error" => "Select must be an array."];
            return json_encode($error);
        }

        if ($selected) {
            $response = (array) json_decode($response);
            $items = [];

            foreach($selected as $select) {
                $search = array_key_exists($select, $response);

                if ($search === true) {
                    $items[$select] = $response[$select];
                }
            }

            $response = json_encode($items);
            return $response;
        }

        return $response;

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
