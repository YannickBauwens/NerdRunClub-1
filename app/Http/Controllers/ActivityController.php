<?php

namespace App\Http\Controllers;

use App;
use App\Activity;
use App\Strava;
use Carbon\Carbon;
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
        // dd ($result);


        foreach ($result as $activity) {
            $newActivity = new Activity;
            $newActivity->strava_activity_id = $activity->id;
            $newActivity->strava_id = $activity->athlete->id;
            $newActivity->distance = $activity->distance;
            $newActivity->start_date = Carbon::parse($activity->start_date)->toDateTimeString();
            $newActivity->save();
        }


        $activities = Activity::all()->where('strava_id', $user->strava_id);
        return view('activities', ['strava_id' => $user->strava_id, 'firstname' => $user->firstname, 'activities' =>
            $result]);
    }
}
