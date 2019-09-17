<?php

use App\Models\Employee;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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

$factory->define(Employee::class, function (Faker $faker) {
    $salary_type_id = $faker->boolean;  // 0 monthly, 1 hourly
    return [
        'fio' => $faker->unique()->name,
        'birthday' => $faker->dateTimeBetween($startDate = '-40 years', $endDate = 'now')->format("Y-m-d"),
        'position' => $faker->word,
        'salary_type_id' => $salary_type_id,
        'salary' => $salary_type_id ? $faker->numberBetween(4,10) : $faker->numberBetween(500, 2000),
        'hours' => $salary_type_id ? $faker->numberBetween(100, 160) : null,
    ];
});
