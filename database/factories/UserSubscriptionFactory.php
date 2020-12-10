<?php

use Faker\Generator as Faker;

$factory->define(App\UserSubscription::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});
