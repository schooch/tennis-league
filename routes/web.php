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

Route::get('/fixture/{id}', 'FixtureController@show');
Route::get('/', 'PagesController@index');
Route::get('/mens', 'PagesController@mens');
Route::get('/ladies', 'PagesController@ladies');
Route::get('/clubs', 'PagesController@clubs');
Route::get('/{club}/{team}', 'PagesController@fixtures');
Route::resource('posts', 'FixtureController');
Auth::routes();

Route::get('/{club}', 'PagesController@club');
