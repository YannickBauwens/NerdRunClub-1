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
        // dd($result);

        //Retrieve the current user's STRAVA ID
        $userStravaId = $result->athlete->id;
        // echo $userStravaId;
        // echo "<hr>";

        // Look for the current user's STRAVA ID in the database's users table
        if ((json_decode(App\User::all()->where('strava_id', $userStravaId), true)) == []) {
            $databaseSearch = 0;
        } else {
            $databaseSearch = (json_decode(App\User::all()->where('strava_id', $userStravaId), true))["30"]['strava_id'];
        }
        // echo $databaseSearch;
        // echo "<hr>";

        //If user exists, update his/her data in the users table. If not, add user to the users table.
        if ($userStravaId == $databaseSearch) {
            // update record
            $updateUser = App\User::where('strava_id', $userStravaId)->first();
            $updateUser->strava_id = $userStravaId;
            $updateUser->firstname = $result->athlete->firstname;
            $updateUser->lastname = $result->athlete->lastname;
            $updateUser->sex = $result->athlete->sex;
            $updateUser->profile = $result->athlete->profile;
            $updateUser->token = $result->access_token;
            $updateUser->save();
            echo "updated!";
            return view('home', ['firstname' => $updateUser->firstname]);
        } else {
            // add record
            $newUser = new App\User();
            $newUser->strava_id = $userStravaId;
            $newUser->firstname = $result->athlete->firstname;
            $newUser->lastname = $result->athlete->lastname;
            $newUser->sex = $result->athlete->sex;
            $newUser->profile = $result->athlete->profile;
            $newUser->token = $result->access_token;
            $newUser->save();
            echo "added!";
            return view('home', $newUser);
        }

    }
}
