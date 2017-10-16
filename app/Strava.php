<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 11-Oct-17
 * Time: 10:33
 */

namespace App;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Strava
{
    public $client;
    public $key;
    public $secret;

    public function __construct($key, $secret)
    {
        $this->client = new Client([
            'base_uri' => 'https://www.strava.com/',
            'timeout' => 2.0,
        ]);

        $this->key = $key;
        $this->secret = $secret;
    }

    public function post($route, array $parameters)
    {
        if ($route == '/oauth/token') {
            $parameters['client_id'] = $this->key;
            $parameters['client_secret'] = $this->secret;
        }

        $r = $this->client->request('POST', $route, [
            'form_params' => $parameters
        ]);

        return json_decode($r->getBody()->getContents());
    }

    public function get($route, array $parameters)
    {
        $r = $this->client->request('GET', $route, [
            'headers' => $parameters
        ]);

        return json_decode($r->getBody()->getContents());
    }
}