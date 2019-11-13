<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Organization;
use Faker\Generator as Faker;

$factory->defineAs(Organization::class, 'organization', function (Faker $faker) {
    return [
        'display_name' => $faker->company,
        'ogrn' => $faker->randomNumber(9, true) . $faker->randomNumber(4, true),
        'oktmo'=> $faker->randomNumber(9, true) . $faker->randomNumber(2, true)
    ];
});