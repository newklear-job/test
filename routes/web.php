<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->to('employees.index');
});
Route::group(['prefix'=> 'employees', 'as'=> 'employees.'], function(){
    Route::get('/{department?}', 'EmployeeController@index')->name('index');
    Route::post('/', 'EmployeeController@importXML')->name('importXML');
    Route::delete('/{department?}', 'EmployeeController@delete')->name('deleteAll');
});

