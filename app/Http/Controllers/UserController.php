<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App;
use App\Strava;

class UserController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function token_exchange()
    {
        // get token from url
        $token = request()->code;

        $strava = new Strava();


        // TODO: naar klasse, 2 parameters: route en array met form params
        $r = $strava->client->request('POST', '/oauth/token', [
            'form_params' => [
                'client_id' => env('STRAVA_ID'),
                'client_secret' => env('STRAVA_SECRET'),
                'code' => $token
            ]
        ]);

        $result = json_decode($r->getBody()->getContents());

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
