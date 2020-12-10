<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ShopQuizz;
use Faker\Generator as Faker;

$factory->define(ShopQuizz::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'name' => $faker->sentence(3),
        'image_url' => $faker->imageUrl(),
        'description' => $faker->text,
        'difficulty' => $faker->numberBetween(1, 3),
        'tags' => $faker->words,
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});
