@extends('layouts.app')

@section('content')
    <div class="col-5 mx-auto" style="margin-top: 200px">
        <div class="row">
            <div class="col-6">
                <form action="{{route('employees.deleteAll', $department)}}" method="POST">
                    @method('delete')
                    @csrf
                    <button>Удалить все данные для текущего отдела</button>
                </form>
            </div>
            <div class="col-6">
                <form action="{{route('employees.importXML')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="xml_file">
                    <button>Импортировать с XML</button>
                    {!! $errors->first('xml_file', '<p style="color:red;">:message</p>') !!}
                    @if (\Session::has('result'))
                        {{\Session::get('result')}}
                        @endif
                </form>
            </div>
        </div>
        <div>
            <h3>Вывод информации для отдела: {{$department->title ?? 'Все отделы'}}</h3>
        </div>
        <div>
            Выбрать отдел
            <select id="changeDepartment">
                <option value="{{route('employees.index')}}" {{$department ? '' : 'selected disabled'}}>Все отделы
                </option>
                @foreach($allDepartments as $departmentID=>$departmentName)
                    <option value="{{route('employees.index', $departmentID)}}"
                            @if($department && $department->id === $departmentID)
                            selected disabled
                            @endif
                    >{{$departmentName}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <form action="">
                Количество записей на странице
                <select name="perPage" id="perPage" onchange="form.submit()">
                    @foreach($paginationOptions as $paginationOption)
                        <option value="{{$paginationOption}}"
                                {{request('perPage', '10') === (string)$paginationOption ? 'selected disabled' : ''}}
                        >{{$paginationOption}}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="content">
            <table id="data-table-command" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Дата рождения</th>
                    <th>Отдел</th>
                    <th>Должносить</th>
                    <th>Тип сотрудника</th>
                    <th>Оплата за месяц</th>
                </tr>
                </thead>
                <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td>{{$employee->fio}}</td>
                        <td>{{$employee->birthday}}</td>
                        <td>{{$employee->department->title}}</td>
                        <td>{{$employee->position}}</td>
                        <td>{{$employee->salary_type}}</td>
                        <td>{{$employee->monthlySalary}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{$employees->appends($_GET)->links()}}
    </div>


@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $("#changeDepartment").change(function () {
            window.location = this.value;
        });
    </script>
@endsection