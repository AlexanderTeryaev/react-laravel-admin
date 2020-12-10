<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ShopTraining;
use Faker\Generator as Faker;

$factory->define(ShopTraining::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'name' => $faker->sentence(3),
        'subtitle' => $faker->text,
        'difficulty' => $faker->numberBetween(1, 3),
        'tags' => $faker->words,
        'image_url' => $faker->imageUrl(),
        'description' => $faker->text,
        'price' => 1,
        'is_published' => $faker->boolean(90)
    ];
});
