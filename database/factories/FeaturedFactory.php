<?php

use Faker\Generator as Faker;

$factory->define(App\Featured::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'name' => $faker->sentence(1),
        'pic_url' => $faker->imageUrl(),
        'description' => $faker->text,
        'order_id' => $faker->randomDigit,
        'is_published' => $faker->boolean(80),
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});

$factory->state(App\Featured::class, 'published', function ($faker) {
    return [
        'is_published' => true
    ];
});

$factory->state(App\Featured::class, 'unpublished', function ($faker) {
    return [
        'is_published' => false
    ];
});