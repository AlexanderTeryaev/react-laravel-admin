<?php

use Faker\Generator as Faker;

$factory->define(App\Quizz::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'name' => $faker->sentence(3),
        'image_url' => $faker->imageUrl(),
        'default_questions_image' => $faker->imageUrl(),
        'description' => $faker->text,
        'enduro_limit' => $faker->numberBetween(0, 20),
        'difficulty' => $faker->numberBetween(1, 3),
        'tags' => $faker->words,
        'is_published' => $faker->boolean(90),
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});

$factory->state(App\Quizz::class, 'published', function ($faker) {
    return [
        'is_published' => true
    ];
});

$factory->state(App\Quizz::class, 'unpublished', function ($faker) {
    return [
        'is_published' => false
    ];
});