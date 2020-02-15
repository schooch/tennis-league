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
Route::get('/', 'PagesController@index');
// Route::get('/mens', 'PagesController@mens');
// Route::get('/ladies', 'PagesController@ladies');

Route::get('/{league}', 'DivisionController@index', function($league){
})->where('league', '(mens|ladies|juniors)');

Route::resources([
    'fixture' => 'FixtureController',
    'players' => 'PlayerController'
    ]);
//Clubs
Route::get('/clubs', 'ClubController@index');
Route::get('/clubs/create', 'ClubController@create');
Route::post('/clubs', 'ClubController@store');
Route::get('/{id}', 'ClubController@show');
Route::get('/{id}/edit', 'ClubController@edit');
Route::put('/{id}', 'ClubController@update');
Route::delete('/{id}', 'ClubController@destroy');

//Teams
Route::get('/{club}/create', 'TeamController@create');
Route::post('/{club}', 'TeamController@store');
Route::get('/{club}/{team}', 'TeamController@show');
Route::get('/{club}/{team}/edit', 'TeamController@edit');
Route::put('/{club}/{team}', 'TeamController@update');
Route::delete('/{club}/{team}', 'TeamController@destroy');

Auth::routes();
