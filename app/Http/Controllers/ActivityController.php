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

        $strava = App::make('App\Strava');
        $data = $strava->get('/api/v3/athlete/activities', ['Authorization' => 'Bearer '.$token]);


        foreach ($data as $activity) {
            $newActivity = Activity::firstOrNew(['strava_activity_id' => $activity->id]);;
            $newActivity->strava_activity_id = $activity->id;
            $newActivity->strava_id = $activity->athlete->id;
            $newActivity->distance = $activity->distance;
            $newActivity->start_date = Carbon::parse($activity->start_date)->toDateTimeString();
            $newActivity->save();
        }


        $activities = Activity::all()->where('strava_id', $user->strava_id);
        return view('activities', ['strava_id' => $user->strava_id, 'firstname' => $user->firstname, 'activities' =>
            $activities]);
    }
}
