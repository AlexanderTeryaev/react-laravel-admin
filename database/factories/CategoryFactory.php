<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'name' => $faker->sentence(2),
        'logo_url' => $faker->imageUrl(),
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});
