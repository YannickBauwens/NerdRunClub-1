<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $strava_id = $user->strava_id;
        $activities = Activity::all()->where('strava_id', $strava_id);
        return view('activities', ['user_id' => $user->id, 'firstname' => $user->firstname, 'activities' => $activities]);
    }
}
