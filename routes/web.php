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

//Route::get('/', function () {
//    return view('welcome');
//});

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'OrgController@index')->name('home');

Route::post('/user/store/org/{org}', 'UserController@store')->name('user.store');

Route::get('/user/{user}', 'UserController@show')->name('user.show');

Route::delete('/user/delete/{user}', 'UserController@destroy')->name('user.destroy');

Route::post('/xml/upload', 'XMLController@upload')->name('xml.upload');

Route::resource('/org', 'OrgController', ['only' => [
    'index', 'create', 'store', 'show', 'destroy'
]]);


