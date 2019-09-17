<?php

namespace App\Services\Employee;

use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class EmployeeService
{
    const MONTHLY_SALARY = 0;
    const HOURLY_SALARY = 1;

    public function parseEmployeesXML($file)
    {
        $xmlRoot = simplexml_load_file($file);

        $created = 0;
        $updated = 0;
        $errors = 0;
        foreach ($xmlRoot as $employee) {
            $employeeArray = [];
            if (!isset($employee->fio) || !isset($employee->birthday) || !isset($employee->department) ||
                !isset($employee->position) || !isset($employee->salary_type) || !isset($employee->salary)) {
                $errors++;
                continue;
            }
            if ($employee->salary_type === 'Почасовая оплата' && !isset($employee->hours)) {
                $errors++;
                continue;
            }
            $employeeArray['fio'] = (string)$employee->fio;
            $employeeArray['birthday'] = \DateTime::createFromFormat('Y-m-d', $employee->birthday);

            if(!$employeeArray['birthday']){
                $errors++;
                continue;
            }

            $employeeArray['department'] = (string)$employee->department;
            $employeeArray['position'] = (string)$employee->position;
            $employeeArray['salary_type_id'] = $this->getSalaryTypeID((string)$employee->salary_type);

            if(is_null($employeeArray['salary_type_id']))
            {
                $errors++;
                continue;
            }

            $employeeArray['salary'] = is_numeric((string)$employee->salary) ? (int)$employee->salary : null;
            if(is_null($employeeArray['salary']))
            {
                $errors++;
                continue;
            }

            if($employeeArray['salary_type_id'] === EmployeeService::HOURLY_SALARY){
                $employeeArray['hours'] = is_numeric((string)$employee->hours) ? (int)$employee->hours : null;
                if(is_null($employeeArray['hours']))
                {
                    $errors++;
                    continue;
                }
            }
            switch($this->proceedRecord($employeeArray)){
                case('created'):
                    $created++;
                    break;
                case('updated'):
                    $updated++;
                    break;
                default:
                    $errors++;
                    break;
            }
        }
        return "created: {$created}, updated: {$updated}, errors: {$errors}";
    }

    public function proceedRecord($employee){
        $department = Department::firstOrCreate(['title' => $employee['department']]);
        $employee['department_id'] = $department->id;
        unset($employee['department']);
        $employeeExists = Employee::where(['fio' => $employee['fio']])->first();
        if ($employeeExists){
            $employeeExists->update($employee);
            return 'updated';
        }
        Employee::create($employee);
        return 'created';
    }

    public function getSalaryTypeID($salary_type){
        if($salary_type === 'Почасовая оплата')
            return EmployeeService::HOURLY_SALARY;
        if($salary_type === 'Ставка')
            return EmployeeService::MONTHLY_SALARY;
        return null;
    }
}
