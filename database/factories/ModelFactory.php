<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Carbon\Carbon;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\Task::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->sentence,
        'complete' => false,
        'next' => $faker->boolean(),
        'priority' => ['LOW','MED','HGH'][rand(0,2)],
        'details' => $faker->paragraph(2),
    ];
});
$factory->define(App\Project::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence,
    ];
});
