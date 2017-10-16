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
       return view('welcome');
    }

    public function token_exchange()
    {
        // get token from url
        $token = request()->code;

        $strava = App::make('App\Strava');
        $data = $strava->post('/oauth/token', ['code' => $token]);

        //Retrieve the current user's STRAVA ID
        $userStravaId = $data->athlete->id;

        // Look for user in database and either update user or make new user
        $user = App\User::firstOrNew(['strava_id' => $userStravaId]);

            $user->strava_id = $userStravaId;
            $user->firstname = $data->athlete->firstname;
            $user->lastname = $data->athlete->lastname;
            $user->sex = $data->athlete->sex;
            $user->profile = $data->athlete->profile;
            $user->token = $data->access_token;
            $user->save();
            auth()->login($user);

        return view('home', ['firstname' => $user->firstname, 'profile' => $user->profile]);

       
    }
}
