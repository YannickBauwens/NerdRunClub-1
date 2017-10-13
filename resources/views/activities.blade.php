<h1>Activities</h1>

<p>Hi {{ $firstname }}, who has id {{ $strava_id }}, these are your activities!</p>

@foreach ($activities as $activity)
    <p>This is activity {{ $activity->id }}, in which you ran {{ $activity->distance }} meters on {{
     Carbon\Carbon::parse($activity->start_date)->format('d/m/Y') }}.</p>
@endforeach