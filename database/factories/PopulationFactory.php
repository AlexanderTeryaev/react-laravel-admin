<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\GroupPopulation::class, function (Faker $faker) {
    return [
        'name' => $faker->jobTitle,
        'description' => $faker->realText(20),
        'master_key' => Str::lower(Str::random(5))
    ];
});
