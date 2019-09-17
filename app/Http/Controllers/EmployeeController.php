<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\ImportXmlRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Services\Employee\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController
{
    public function index(Request $request, Department $department=null)
    {
        $perPage = is_numeric($request->perPage) ? (int)$request->perPage : 10;
        $employees = Employee::getEmployees($department)->with('department')->paginate($perPage);
        $allDepartments = Department::pluck('title', 'id');
        $paginationOptions = [10, 25, 50, 100];
        return view('employees.index', compact('department', 'employees', 'allDepartments',
            'paginationOptions'));
    }

    public function importXML(ImportXmlRequest $request, EmployeeService $employeeService)
    {
        $result = $employeeService->parseEmployeesXML($request->file('xml_file'));
        return redirect()->route('employees.index')->with('result', $result);
    }

    public function delete(Request $request, Department $department=null)
    {
        Employee::getEmployees($department)->delete();
        if($department){
            $department->delete();
        }
        else{
            Department::query()->delete();
        }
        return redirect()->route('employees.index');
    }
}
