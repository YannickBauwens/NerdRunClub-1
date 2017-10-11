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

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://www.strava.com/',
            'timeout' => 2.0,
        ]);
    }


}