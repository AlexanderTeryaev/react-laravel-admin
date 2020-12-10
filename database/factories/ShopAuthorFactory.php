<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ShopAuthor;
use Faker\Generator as Faker;

$factory->define(ShopAuthor::class, function (Faker $faker) {
    $created_at = $faker->dateTimeThisYear;
    return [
        'name' => $faker->name,
        'pic_url' =>  $faker->imageUrl(),
        'function' => $faker->jobTitle,
        'description' => $faker->sentence(30),
        'fb_link' => $faker->url,
        'twitter_link' => $faker->url,
        'website_link' => $faker->url,
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});
