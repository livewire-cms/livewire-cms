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

Route::group([
    'prefix' => 'backend/abc',
    'middleware' => ['web','auth'],
    'as'=> 'backend.abc.',
],function () {
    Route::get('/', 'AbcController@index')->name('index');
});
Route::group([
    'prefix' => 'backend/hello',
    'middleware' => ['web','auth'],
    'as'=> 'backend.hello.',
], function () {
    Route::group([
        'prefix'=>'hello',
        'as' =>'hello.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Hello@index')->name('');
        Route::get('create', 'Hello@create')->name('create');
        Route::get('update/{id}', 'Hello@update')->name('update');
    });
});Route::group([
    'prefix' => 'backend/abc',
    'middleware' => ['web','auth'],
    'as'=> 'backend.abc.',
], function () {
    Route::group([
        'prefix'=>'hello',
        'as' =>'hello.',
        'middleware' =>[]
    ],function () {
        Route::get('', 'Hello@index')->name('');
        Route::get('create', 'Hello@create')->name('create');
        Route::get('update/{id}', 'Hello@update')->name('update');
    });
});