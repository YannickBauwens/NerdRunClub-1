<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activities = Activity::all()->where('strava_id', $user->strava_id);
        return view('activities', ['strava_id' => $user->strava_id, 'firstname' => $user->firstname, 'activities' =>
            $activities]);
    }
}
