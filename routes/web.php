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
//
//
Route::resources([
    'fixture' => 'FixtureController'
    ]);
Route::get('/clubs', 'ClubController@index');
Route::get('/clubs/create', 'ClubController@create');
Route::post('/clubs', 'ClubController@store');
Route::get('/{id}', 'ClubController@show');
Route::get('/{id}/edit', 'ClubController@edit');
Route::put('/{id}', 'ClubController@update');
Route::delete('/{id}', 'ClubController@destroy');


Route::get('/', 'PagesController@index');
Route::get('/mens', 'PagesController@mens');
Route::get('/ladies', 'PagesController@ladies');
Route::get('/{club}/{team}', 'PagesController@fixtures');
Auth::routes();

