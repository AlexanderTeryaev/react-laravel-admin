<?php

use Faker\Generator as Faker;

$factory->define(\App\Admin::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'name' => $faker->firstName,
        'email' => $faker->safeEmail,
        'password' => '$2y$10$C/KuFdhNvYJ15TH.8ImC/uiCr6vJhO8EGNk7ARodtpQ.JvmEzd7cy',
        'remember_token' => $faker->md5,
        'status' => $faker->boolean(80),
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});
