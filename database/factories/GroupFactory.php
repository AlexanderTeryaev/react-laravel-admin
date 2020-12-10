<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Group::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'name' => $faker->company,
        'logo_url' => $faker->imageUrl(),
        'status' => true,
        'description' => $faker->sentence(10),
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});

$factory->state(App\Group::class, 'onTrial', [
    'trial_ends_at' => now()->addDays(10)
]);

$factory->state(App\Group::class, 'closed', [
    'trial_ends_at' => now()->subDay()
]);
