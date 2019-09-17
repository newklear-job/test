<?php

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Employee;
class EmployeesAndDepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employee::query()->delete();
        Department::query()->delete();
        factory(Department::class, 5)->create()->each(function ($category) {
            $category->employees()->saveMany(factory(Employee::class, 10)->make());
        });
    }
}
