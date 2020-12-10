<?php

use Faker\Generator as Faker;
use TaylorNetwork\UsernameGenerator\Facades\UsernameGenerator;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    $curr_os = ($faker->numberBetween(1, 2) == 1) ? 'ios' : 'android';
    $username = UsernameGenerator::generate();
    $tm_namespace = 'rzfas.';
    $tm_domain = '@inbox.testmail.app';
    $created_at = $faker->dateTimeThisYear('now');

    return [
        'first_name' => $faker->unique()->firstName,
        'last_name' => $faker->unique()->lastName,
        'email' => $tm_namespace . $username . $tm_domain,
        'password' => \Illuminate\Support\Facades\Hash::make('toto42sh'),
        'device_id' => $faker->uuid,
        'last_ip' => $faker->ipv4,
        'username' => $username,
        'bio' => $faker->sentence(4),
        'current_group' => 1,
        'curr_os' => $curr_os,
        'curr_app_version' => '3.0.3',
        'reputation' => $faker->randomDigit,
        'can_submit_report' => $faker->boolean(98),
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});

$factory->state(App\User::class, 'verified', function ($faker) {
    return [
        'email_verified_at' => $faker->dateTime
    ];
});
