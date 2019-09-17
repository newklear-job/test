<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    const MONTHLY_SALARY = 0;
    const HOURLY_SALARY = 1;

    protected $guarded = ['id'];
    protected $appends = ['salaryType', 'monthlySalary'];

    public function scopeGetEmployees($query, $department = null)
    {
        return $query->when($department, function ($query) use ($department) {
            return $query->where('department_id', $department->id);
        });
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function isMonthlyType(){
        if ($this->salary_type_id == Employee::MONTHLY_SALARY){
            return true;
        }
        return false;
    }

    public function isHourlyType(){
        if ($this->salary_type_id == Employee::HOURLY_SALARY){
            return true;
        }
        return false;
    }

    public function getSalaryTypeAttribute()
    {
        if($this->isHourlyType()){
            return 'Почасовая оплата';
        }
        else if ($this->isMonthlyType()){
            return 'Ставка';
        }
        return 'Ошибка';
    }

    public function getMonthlySalaryAttribute(){
        if ($this->isHourlyType()){
            return $this->salary * $this->hours;
        }
        return $this->salary;
    }


}
