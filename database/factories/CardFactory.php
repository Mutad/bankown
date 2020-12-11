<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Card;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'balance' => $faker->numberBetween(10,100),
        'currency'=> $faker->currencyCode,
        'user_id'=> User::all()->random()->id,
        'type'=>'DEBIT',
        'number'=>$faker->creditCardNumber
    ];
});