<?php

use Faker\Generator as Faker;

$factory->define(App\Activity::class, function (Faker $faker) {
    return [
        'strava_activity_id' => $faker->randomNumber($nbDigits = 6),
        'strava_id' => $faker->randomNumber($nbDigits = 5),
        'distance' => $faker->randomFloat($nbMaxDecimals = 1, $min = 0, $max = 10000),
        'start_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];

});
