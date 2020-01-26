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

Route::resources([
    'fixture' => 'FixtureController',
    'clubs' => 'ClubController'
    ]);
Route::get('/', 'PagesController@index');
Route::get('/mens', 'PagesController@mens');
Route::get('/ladies', 'PagesController@ladies');
Route::get('/{club}/{team}', 'PagesController@fixtures');
Auth::routes();

Route::get('/{club}', 'PagesController@club');
