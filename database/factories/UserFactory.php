<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->defineAs(User::class, 'user', function (Faker $faker) {
    return [
        'name'          => $faker->name,
        'last_name'     => $faker->lastName,
        'middle_name'   => 'Unknown',
        'inn'           => $faker->randomNumber(9, true) . $faker->randomNumber(7, true),
        'snils'         => $faker->randomNumber(9, true) . $faker->randomNumber(4, true),
        'birthday'      => $faker->date(),
        'org_id'        => $faker->numberBetween(1, 10)
    ];
});


