<h1>Activities</h1>

<p>Hi {{ $firstname }}, who has id {{ $strava_id }}, these are your activities!</p>

@foreach ($activities as $activity)
    <p>This is activity {{ $activity->id }}</p>
@endforeach