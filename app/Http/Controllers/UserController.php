<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class UserController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function token_exchange()
    {
        $token = request()->code;

        //https://www.strava.com/oauth/{token}
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://www.strava.com',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $r = $client->request('POST', 'https://www.strava.com/token', [
            'form_params' => [
                'client_id' => env('STRAVA_ID'),
                'client_secret' => env('STRAVA_SECRET'),
                'code' => $token
            ]
        ]);

        dd($r);
    }
}
