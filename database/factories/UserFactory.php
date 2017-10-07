<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    // static $password;

    return [
        'strava_id' => $faker->randomNumber($nbDigits = 6),
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'sex' => $faker->randomElement($array = array ('M','F')),
        'profile' => $faker->imageUrl($width = 124, $height = 124),
        'token' => str_random(10),
    ];
});

