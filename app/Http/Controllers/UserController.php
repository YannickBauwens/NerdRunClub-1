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
        // token uit url halen
        $token = request()->code;
        // Guzzle client opzetten = naar wie ga je requests sturen
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://www.strava.com/oauth/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        // Http request uitvoeren, we geven client_id, client_secret en de verkregen code mee als parameter
        $r = $client->request('POST', 'token', [
            'form_params' => [
                'client_id' => env('STRAVA_ID'),
                'client_secret' => env('STRAVA_SECRET'),
                'code' => $token
            ]
        ]);

        // Inhoud van de body van wat we terugkrijgen in variabele steken -> bevat access_token en user info
        $result = json_decode ($r->getBody()->getContents());
        //$access_token = $result->{'access_token'};
        //$username = $result->{'athlete'}->{'username'};
        dd($result);
    }
}
