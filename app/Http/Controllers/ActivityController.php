<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Strava;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $token = $user->token;

        $strava = new Strava();
        $a = $strava->client->request('GET', '/api/v3/athlete/activities', [
            'headers' => [
                'Authorization' => 'Bearer '.$token
            ]
        ]);
        $result = json_decode($a->getBody()->getContents());
        dd ($result);


        $activities = Activity::all()->where('strava_id', $user->strava_id);
        return view('activities', ['strava_id' => $user->strava_id, 'firstname' => $user->firstname, 'activities' =>
            $activities]);
    }
}
