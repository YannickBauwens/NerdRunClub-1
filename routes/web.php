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

Route::get('/', 'UserController@login');

Route::get('/home', function() {
    return view ('home');
});

Route::get('/activities', 'ActivityController@index');

// Route::get('login', 'UserController@login');

Route::get('token_exchange', 'UserController@token_exchange');

