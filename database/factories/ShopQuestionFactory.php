<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ShopQuestion;
use Faker\Generator as Faker;

$factory->define(ShopQuestion::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'question' => $faker->sentence(8). '?',
        'good_answer' => $faker->sentence(3),
        'bad_answer' => $faker->sentence(3),
        'bg_url' => $faker->imageUrl(),
        'difficulty' => $faker->numberBetween(1, 3),
        'more' => $faker->sentence(50),
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});
