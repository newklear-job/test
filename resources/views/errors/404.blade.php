@extends('layouts.app')

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <h1>404</h1>
            <h3>Данная страница не доступна!</h3>
            <button type="button" class="btn btn-error"><a href="{{route('employees.index')}}">Перейти ко всем сотрудникам</a>
            </button>
        </div>
    </div>
@endsection


