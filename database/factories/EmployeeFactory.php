<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Http\Model\Employee::class, function (Faker $faker) {
    return [
        'employee_name' => $faker->name,
        'employee_address' => $faker->address,
        'employee_type' => $faker->randomElement(['Laundry' ,'Agen', 'Steam']),
        'employee_sallary' => $faker->numberBetween($min = 30000, $max = 50000),
        'employee_active' => '1',
        'employee_created_by' => '1',
        'employee_created_at' => now()
    ];
});
