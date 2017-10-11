<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App;

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

        // Guzzle client opzetten = naar wie/waar ga je requests sturen
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://www.strava.com/oauth/',
            // You can set any number of default request options.
            'timeout' => 2.0,
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
        $result = json_decode($r->getBody()->getContents());
        //$access_token = $result->{'access_token'};
        //$username = $result->{'athlete'}->{'username'};
            // dd($result);

        //Retrieve the current user's STRAVA ID
        $userStravaId = $result->athlete->id;
            // echo $userStravaId;
            // echo "<hr>";


        // Look for user in database and either update user or make new user


        $checkUser = App\User::firstOrNew(['strava_id' => $userStravaId]);

        if ($checkUser) {
            $checkUser->strava_id = $userStravaId;
            $checkUser->firstname = $result->athlete->firstname;
            $checkUser->lastname = $result->athlete->lastname;
            $checkUser->sex = $result->athlete->sex;
            $checkUser->profile = $result->athlete->profile;
            $checkUser->token = $result->access_token;
            $checkUser->save();
        }

        return view('home', ['firstname' => $checkUser->firstname, 'profile' => $checkUser->profile]);

       
    }
}
