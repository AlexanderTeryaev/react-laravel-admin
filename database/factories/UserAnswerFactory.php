<?php

use Faker\Generator as Faker;

$factory->define(App\UserAnswer::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'result' => $faker->numberBetween(0, 1),
        'ip' => $faker->ipv4,
        'is_enduro' => 0,
        'answered_at' => $faker->dateTimeInInterval($created_at, '+'. mt_rand(1,3) . ' hours'),
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});
