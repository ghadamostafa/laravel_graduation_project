<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Design;
use Faker\Generator as Faker;

$factory->define(Design::class, function (Faker $faker) {
    return [
        //
        'tag_id' => $faker->numberBetween(1,10),
        'company_id' => null,
        'designer_id' => $faker->numberBetween(1,10),
        'description' => $faker->text,
        'title' => $faker->catchPhrase,
        'price' => $faker->randomFloat(2,10,100),
        'state' => "sketch",
        'category' => "men",
        'source_file' => 'uploads/'.$faker->name,
    ];
});
