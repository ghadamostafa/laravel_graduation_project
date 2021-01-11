<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CompanyDesign;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(CompanyDesign::class, function (Faker $faker) {
    return [
        'design_id' => $faker->numberBetween(1, 10),
        'company_id' => $faker->numberBetween(1, 10),
        'title' => $faker->title,
        'price' => $faker->randomFloat(),
        'link' => "www.google.com",
        'image' => 'uploads/' . Str::random(10),

    ];
});
