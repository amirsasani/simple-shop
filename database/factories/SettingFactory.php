<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Setting;
use Faker\Generator as Faker;

$factory->define(Setting::class, function (Faker $faker) {
    return [
        'setting_key' => 'bankacount_number',
        'setting_value' => '9999',
    ];
});
