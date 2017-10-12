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
        return view('welcome');
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


        $user = App\User::firstOrNew(['strava_id' => $userStravaId]);

            $user->strava_id = $userStravaId;
            $user->firstname = $result->athlete->firstname;
            $user->lastname = $result->athlete->lastname;
            $user->sex = $result->athlete->sex;
            $user->profile = $result->athlete->profile;
            $user->token = $result->access_token;
            $user->save();
            auth()->login($user);

        return view('home', ['firstname' => $user->firstname, 'profile' => $user->profile]);

       
    }
}
